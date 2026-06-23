<?php

return [
    'api_key'   => env('AFRICA_TALKING_API'),
    'username'  => env('AFRICA_TALKING_USERNAME', 'sandbox'),
    'sender_id' => env('AFRICA_TALKING_SENDER_ID'),
    'base_url'  => env('AFRICA_TALKING_USERNAME', 'sandbox') === 'sandbox'
        ? 'https://api.sandbox.africastalking.com'
        : 'https://api.africastalking.com',
];
