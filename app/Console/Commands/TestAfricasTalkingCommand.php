<?php

namespace App\Console\Commands;

use App\Services\AfricasTalkingService;
use Illuminate\Console\Command;

class TestAfricasTalkingCommand extends Command
{
    protected $signature = 'at:test {--number= : Phone number to send to (e.g. +254711XXXXXX)}';

    protected $description = 'Send a test SMS via Africa\'s Talking to verify the integration';

    public function handle(AfricasTalkingService $sms): int
    {
        $number = $this->option('number');

        if (! $number) {
            $number = $this->ask('Phone number to send to (e.g. +254711XXXXXX)');
        }

        $this->info("Username : " . config('africastalking.username'));
        $this->info("Endpoint : " . config('africastalking.sms_endpoint'));
        $this->info("Sending to: {$number}");

        $sent = $sms->sendSms([$number], 'Test SMS from Ticketeke — Africa\'s Talking integration is working!');

        if ($sent) {
            $this->info('SMS sent successfully.');
            return self::SUCCESS;
        }

        $this->error('SMS failed. Check logs for details.');
        return self::FAILURE;
    }
}
