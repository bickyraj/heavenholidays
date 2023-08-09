<?php

return [
	'default_image_url' => env('WEB_URL') . '/img/default-sm.gif',
	'default_large_image_url' => env('WEB_URL') . '/img/default.gif',
	'default_hero_banner' => env('WEB_URL') . '/img/hero.jpg',
	// 'google_recaptcha' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
    'google_recaptcha' => '6Lc12L4UAAAAAIfI7dV4SxDrX1ZrmtZMjbe9YjVw',
    'recaptcha' => [
        'sitekey' => '6Lc12L4UAAAAAIfI7dV4SxDrX1ZrmtZMjbe9YjVw',
        'secret' => '6Lc12L4UAAAAAAERNEm9ChpTh0MVKZArWh-9RF4Z',
    ],
    'payment_merchant_id' => env('PAYMENT_MID'),
    'payment_merchange_name' => env('PAYMENT_MERCHANT_NAME'),
    'payment_merchant_key' => env('PAYMENT_SECRET_KEY'),
];
