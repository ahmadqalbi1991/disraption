<?php
return [
    'operations' => [
        'c'     => 'Create',
        'r'     => 'Read',
        'u'     => 'Edit',
        'd'     => 'Delete',
    ],
    'site_modules' => [

        //dashboard
        'dashboard'           => ['name' => 'Dashboard', 'operations' => ['r']],

        //admin user
        'admin_users'         => ['name' => 'Admin Users', 'operations' => ['c', 'r', 'u', 'd']],
        'user_roles'          => ['name' => 'User Roles', 'operations' => ['c', 'r', 'u', 'd']],

        # User: Customer
        'customers'           => ['name' => 'Customers: User Management', 'operations' => ['c', 'r', 'u', 'd']], // User: Customer
        'customers_booking_order'           => ['name' => 'Customers: Booking Orders', 'operations' => ['r']], // Order: Customer
        'reporting_customers_booking_order'           => ['name' => 'Customers Booking Orders: Reporting', 'operations' => ['c', 'r', 'u', 'd']], // Order: Customer
        'customers_transactions'           => ['name' => 'Customers: Transactions', 'operations' => ['r']], // Customer transactions
        'reporting_customers_transactions'           => ['name' => 'Customer Transactions: Reporting', 'operations' => ['r']], // Customer transactions reporting
        'reporting_customers'           => ['name' => 'Customers: Reporting', 'operations' => ['r']], // Reporting: Customer

         # Customer Ratings
         'customer_ratings'           => ['name' => 'Customer Ratings', 'operations' => ['c', 'r', 'u', 'd']], // # Ratings
         'reporting_customer_rating'     => ['name' => 'Customer Ratings: Reporting', 'operations' => ['r']], # Reporting ratings

        # User: Artist/Vendor
        'vendors'     => ['name' => 'Artists: User Management', 'operations' => ['c', 'r', 'u', 'd']],
        'vendors_portfolio'           => ['name' => 'Artist Portfolio', 'operations' => ['c']], // # Portfolio
        'vendors_booking'     => ['name' => 'Artists: Booking', 'operations' => ['c', 'r', 'u', 'd']], # Booking
        'reporting_vendors'     => ['name' => 'Artists: Reporting', 'operations' => ['r']], # Reporting vendor
        'reporting_vendors_booking'     => ['name' => 'Artists Booking: Reporting', 'operations' => ['r']], # Reporting

        # Masters
        'masters_country'             => ['name' => 'Master: Country', 'operations' => ['c', 'r', 'u', 'd']],
        'masters_category'               => ['name' => 'Master: Category', 'operations' => ['c', 'r', 'u', 'd']],
        'masters_app_banners'         => ['name' => 'Master: App Banners', 'operations' => ['c', 'r', 'u', 'd']],
        'masters_booking_resources'         => ['name' => 'Master: Workstations', 'operations' => ['c', 'r', 'u', 'd']],

        # Cms Pages
        'cms_pages'           => ['name' => 'Cms Pages', 'operations' => ['c', 'r', 'u', 'd']],
        'settings'           => ['name' => 'Settings', 'operations' => ['c', 'r', 'u', 'd']],
        'cms_rechedule_policy'   => ['name' => 'Reschedule Policy', 'operations' => ['u']],
        'cms_location'   => ['name' => 'Location', 'operations' => ['u']],
        'cms_cancellation_policy'   => ['name' => 'Cancellation Policy', 'operations' => ['u']],

        #Contact us
        'contact_us_entries'           => ['name' => 'Contact Us', 'operations' => ['r']],


        
        # Vendor Ratings
        'vendor_ratings'           => ['name' => 'Artist Ratings', 'operations' => ['c', 'r', 'u', 'd']], // # Ratings
        'reporting_vendors_rating'     => ['name' => 'Artists Ratings: Reporting', 'operations' => ['r']], # Reporting ratings

    ],
    'user_type_id_permissions' => [
        '3' => [
            'dashboard'  => ['name' => 'Dashboard', 'operations' => ['r']],
            'customers_booking_order'  => ['name' => 'Customers: Booking Orders', 'operations' => ['c']],
            'vendors'     => ['name' => 'Artists: User Management', 'operations' => ['u']],
            'vendors_portfolio'  => ['name' => 'Artist Portfolio', 'operations' => ['c']],
            'vendors_booking' => ['name' => 'Artists: Booking', 'operations' => ['c', 'r', 'u', 'd']],
            'reporting_vendors' => ['name' => 'Artists: Reporting', 'operations' => ['r']],
            'reporting_vendors_booking' => ['name' => 'Artists Booking: Reporting', 'operations' => ['r']],
        ],
    ]
];
