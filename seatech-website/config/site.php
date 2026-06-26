<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Contact Information
    |--------------------------------------------------------------------------
    |
    | Update these values with the official SEATECH Legazpi contact details.
    | They are used in the public footer, contact page, and JSON-LD
    | structured data on the homepage.
    |
    */

    'name' => 'SEATECH Maritime Training & Assessment Center, Inc.',
    'short_name' => 'SEATECH Legazpi',
    'tagline' => 'Maritime Training Center & Assessment Center, Inc.',

    'address' => [
        'street' => 'Legazpi City',
        'city' => 'Legazpi City',
        'province' => 'Albay',
        'country' => 'Philippines',
        'country_code' => 'PH',
    ],

    'contact' => [
        'phone' => '+63 (XXX) XXX-XXXX',
        'phone_raw' => '+63XXXXXXXXXX',
        'email' => 'info@seatechmaritime.com',
    ],

    'office_hours' => [
        'weekdays' => '8:00 AM - 5:00 PM',
        'saturday' => '9:00 AM - 12:00 PM',
        'sunday' => 'Closed',
    ],

    'social' => [
        'facebook' => null,
        'instagram' => null,
        'youtube' => null,
        'linkedin' => null,
    ],

    'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124240.74800137316!2d123.70017!3d13.13906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a101fbcd5b4e5f%3A0x2bb1f0b2c0e0f0f0!2sLegazpi%20City%2C%20Albay!5e0!3m2!1sen!2sph!4v1700000000000',

    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    |
    | Editable content shown on the public About page. The story array
    | supports {city}, {province}, and {name} placeholders that are
    | automatically replaced with the values above.
    |
    */

    'mission' => 'To provide world-class maritime education and training that meets international standards, producing competent, disciplined, and highly skilled seafarers who contribute to the global maritime industry.',

    'vision' => 'To be the leading maritime training and assessment center in the Bicol Region, recognized for excellence in seafarer education, innovation in training methodologies, and unwavering commitment to maritime safety and professionalism.',

    'story' => [
        'Founded in {city}, {province}, {name} has grown to become one of the leading maritime training institutions in the Bicol Region.',
        'With a commitment to international standards and the safety of seafarers worldwide, we provide STCW-compliant training programs that meet the requirements of maritime industry stakeholders.',
        'Our modern facilities, experienced instructors, and comprehensive curriculum have produced thousands of competent seafarers serving on vessels across the world\'s oceans.',
    ],

    'years_excellence' => 15,

    /*
    |--------------------------------------------------------------------------
    | SEO Defaults
    |--------------------------------------------------------------------------
    */

    'seo' => [
        'site_name' => 'SEATECH Maritime Training',
        'default_description' => 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol',
        'default_keywords' => 'maritime training, MARINA accredited, Bicol, Legazpi, seafarer training, STCW',
    ],

];
