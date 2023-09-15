<?php
return [
    'proxy' => [
        'rent_house' => [
            'index' => env('PROXY_RENT_HOUSE_INDEX'),
            'get_csrf_token_and_cookies' => env('PROXY_RENT_HOUSE_INDEX'),
            'list' => env('PROXY_RENT_HOUSE_LIST'),
            'detail_data' => env('PROXY_RENT_HOUSE_DETAIL_DATA'),
        ]
    ]
];
