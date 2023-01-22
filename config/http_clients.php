<?php

return [
    'nytimes' => [
        'api_key' => env('NYTIMES_API_KEY'),
        'offset_divisor' => env('NYTIMES_OFFSET_MULTIPLIER', 20),
        'endpoints' => [
            'bestsellers' => 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json',
        ],
    ],
];
