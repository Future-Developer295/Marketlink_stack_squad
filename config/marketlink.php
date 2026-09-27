<?php

return [

    /*
    |--------------------------------------------------------------------
    | Default Platform Commission Rate (%)
    |--------------------------------------------------------------------
    |
    | Applied to every order unless the farmer has their own
    | commission_rate set (farmer_profile.commission_rate overrides this).
    | Example: 10.00 means the platform keeps 10% of each order's
    | total_amount and the farmer is paid out the remaining 90%.
    |
    */

    'default_commission_rate' => env('MARKETLINK_DEFAULT_COMMISSION_RATE', 10.00),

];
