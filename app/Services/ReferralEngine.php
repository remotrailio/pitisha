<?php

namespace App\Services;

use App\Enums\ReferralStatus;
use App\Enums\RewardStatus;
use App\Enums\RewardType;
use App\Models\Event;
use App\Models\EventReferral;
use App\Models\EventReferralReward;
use App\Models\Order;
use App\Models\User;

class ReferralEngine
{
    /**
     * Determine whether a user may generate or see referral links for an event.
     */
    public static function isEligible(User $user, Event $event): bool
    {
        if (! $user->isAttendee()) {
            return false;
        }

        $event->loadMissing('organizer');

        if ($event->organizer && $event->organizer->user_id === $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Build the referral link for a user on a given event.
     */
    public static function referralLink(User $user, Event $event): string
    {
        return route('events.show', $event->slug) . '?ref=' . $user->referral_code;
    }

    /**
     * Record a referral after a successful payment.
     * Called from MpesaCallbackController immediately after markPaid().
     */
    public static function record(Order $order): void
    {
        if (! $order->referrer_code) {
            return;
        }

        $order->loadMissing(['event.organizer', 'user']);

        $event = $order->event;

        if (! $event || ! $event->enable_referrals) {
            return;
        }

        $referrer = User::where('referral_code', $order->referrer_code)->first();

        if (! $referrer) {
            return;
        }

        $buyer = $order->user;

        // Anti-abuse: no self-referrals
        if ($referrer->id === $buyer->id) {
            return;
        }

        // Anti-abuse: referrer must be an eligible attendee
        if (! static::isEligible($referrer, $event)) {
            return;
        }

        // Anti-abuse: one counted referral per buyer per event
        if (EventReferral::where('event_id', $event->id)->where('referred_user_id', $buyer->id)->exists()) {
            return;
        }

        EventReferral::create([
            'event_id'          => $event->id,
            'referrer_user_id'  => $referrer->id,
            'referred_user_id'  => $buyer->id,
            'referred_order_id' => $order->id,
            'status'            => ReferralStatus::QUALIFIED,
        ]);

        static::checkAndIssueReward($event, $referrer);
    }

    /**
     * Issue a reward when the referrer crosses a target threshold.
     * Supports future multiple-milestone model: each full target crossed earns one reward.
     */
    public static function checkAndIssueReward(Event $event, User $referrer): void
    {
        if (! $event->referral_target || ! $event->reward_type) {
            return;
        }

        $qualifiedCount = EventReferral::where('event_id', $event->id)
            ->where('referrer_user_id', $referrer->id)
            ->where('status', ReferralStatus::QUALIFIED)
            ->count();

        $expectedRewards = (int) floor($qualifiedCount / $event->referral_target);

        if ($expectedRewards === 0) {
            return;
        }

        $issuedRewards = EventReferralReward::where('event_id', $event->id)
            ->where('user_id', $referrer->id)
            ->whereIn('status', [RewardStatus::EARNED->value, RewardStatus::CLAIMED->value])
            ->count();

        if ($issuedRewards >= $expectedRewards) {
            return;
        }

        EventReferralReward::create([
            'event_id'             => $event->id,
            'user_id'              => $referrer->id,
            'reward_type'          => $event->reward_type,
            'reward_ticket_type_id' => $event->reward_ticket_type_id,
            'reward_value'         => $event->reward_value,
            'status'               => RewardStatus::EARNED,
        ]);
    }

    /**
     * Get referral progress for a user on an event.
     */
    public static function getProgress(Event $event, User $user): array
    {
        $count = EventReferral::where('event_id', $event->id)
            ->where('referrer_user_id', $user->id)
            ->where('status', ReferralStatus::QUALIFIED)
            ->count();

        $latestReward = EventReferralReward::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->whereIn('status', [RewardStatus::EARNED->value, RewardStatus::CLAIMED->value])
            ->latest()
            ->first();

        return [
            'count'         => $count,
            'target'        => $event->referral_target ?? 0,
            'latest_reward' => $latestReward,
            'link'          => static::referralLink($user, $event),
        ];
    }
}
