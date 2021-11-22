<?php
$base_url = env('APP_URL');
return [

    'pagination_records' => 10,
    'user_type' => ['User', 'Admin'],
    'FCM_URL' => "https://fcm.googleapis.com/fcm/send",
    'FCM_API_KEY' => "AAAAHFh1zJc:APA91bF3o9-4m9OtPoTsz2dYmIr40cmIWtCcsh89uGFYKe-onUDzTh-8Fjut2X6B2g_ltsvL89k-jmIAsYne0LY7ydvma3_04_rOlqOfxJan1b9fTwPBXJUXpjShgipbTaraJOkvbXkr",
    'DRIVER_IMAGE_PATH' => PHP_SAPI === 'cli' ? false : $base_url . '/uploads/driver/',
    'RIDER_IMAGE_PATH' => PHP_SAPI === 'cli' ? false : $base_url . '/uploads/rider/',
    'VEHICLE_MODEL_IMAGE_PATH' => PHP_SAPI === 'cli' ? false : $base_url . '/uploads/vehicle_model/',
];
