<?php

$isSandbox = env('AFRICA_TALKING_USERNAME', 'sandbox') === 'sandbox';

return [
    'api_key'      => env('AFRICA_TALKING_API'),
    'username'     => env('AFRICA_TALKING_USERNAME', 'sandbox'),
    'sender_id'    => env('AFRICA_TALKING_SENDER_ID'),
    'sms_endpoint' => $isSandbox
        ? 'https://api.sandbox.africastalking.com/version1/messaging'
        : 'https://api.africastalking.com/version1/messaging/bulk',
];
