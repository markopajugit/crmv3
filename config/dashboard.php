<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the dashboard display and behavior.
    |
    */

    // Show totals (companies, persons, orders) to all users
    // Set to false to restrict totals to admins only
    'show_totals_to_all_users' => true,

    // Default time period for dashboard metrics (in days)
    'default_period' => 30,

    // Available time period options
    'period_options' => [7, 30],

    // Chart configuration
    'charts' => [
        'enabled' => true,
        'library' => 'chartjs', // 'chartjs' or 'other'
    ],

    // Number of recent items to show
    'recent_orders_limit' => 10,
    'recent_activity_limit' => 10,
];

