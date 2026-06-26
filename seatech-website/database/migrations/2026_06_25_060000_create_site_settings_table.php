<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        $defaults = [
            // Company identity
            ['key' => 'name', 'value' => 'SEATECH Maritime Training & Assessment Center, Inc.', 'type' => 'string', 'group' => 'company', 'label' => 'Full Company Name', 'sort_order' => 1],
            ['key' => 'short_name', 'value' => 'SEATECH Legazpi', 'type' => 'string', 'group' => 'company', 'label' => 'Short Name', 'sort_order' => 2],
            ['key' => 'tagline', 'value' => 'Maritime Training Center', 'type' => 'string', 'group' => 'company', 'label' => 'Tagline', 'sort_order' => 3],

            // Address
            ['key' => 'address.street', 'value' => 'Legazpi City', 'type' => 'string', 'group' => 'address', 'label' => 'Street / Building', 'sort_order' => 1],
            ['key' => 'address.city', 'value' => 'Legazpi City', 'type' => 'string', 'group' => 'address', 'label' => 'City', 'sort_order' => 2],
            ['key' => 'address.province', 'value' => 'Albay', 'type' => 'string', 'group' => 'address', 'label' => 'Province / State', 'sort_order' => 3],
            ['key' => 'address.country', 'value' => 'Philippines', 'type' => 'string', 'group' => 'address', 'label' => 'Country', 'sort_order' => 4],
            ['key' => 'address.country_code', 'value' => 'PH', 'type' => 'string', 'group' => 'address', 'label' => 'Country Code (2-letter ISO)', 'sort_order' => 5],

            // Contact
            ['key' => 'contact.phone', 'value' => '+63 (XXX) XXX-XXXX', 'type' => 'string', 'group' => 'contact', 'label' => 'Display Phone', 'sort_order' => 1],
            ['key' => 'contact.phone_raw', 'value' => '+63XXXXXXXXXX', 'type' => 'string', 'group' => 'contact', 'label' => 'Raw Phone (for tel: links)', 'sort_order' => 2],
            ['key' => 'contact.email', 'value' => 'info@seatechmaritime.com', 'type' => 'string', 'group' => 'contact', 'label' => 'Email', 'sort_order' => 3],

            // Office hours
            ['key' => 'hours.weekdays', 'value' => '8:00 AM - 5:00 PM', 'type' => 'string', 'group' => 'hours', 'label' => 'Weekdays (Mon-Fri)', 'sort_order' => 1],
            ['key' => 'hours.saturday', 'value' => '9:00 AM - 12:00 PM', 'type' => 'string', 'group' => 'hours', 'label' => 'Saturday', 'sort_order' => 2],
            ['key' => 'hours.sunday', 'value' => 'Closed', 'type' => 'string', 'group' => 'hours', 'label' => 'Sunday', 'sort_order' => 3],

            // Social media (nullable)
            ['key' => 'social.facebook', 'value' => null, 'type' => 'string', 'group' => 'social', 'label' => 'Facebook URL', 'sort_order' => 1],
            ['key' => 'social.instagram', 'value' => null, 'type' => 'string', 'group' => 'social', 'label' => 'Instagram URL', 'sort_order' => 2],
            ['key' => 'social.youtube', 'value' => null, 'type' => 'string', 'group' => 'social', 'label' => 'YouTube URL', 'sort_order' => 3],
            ['key' => 'social.linkedin', 'value' => null, 'type' => 'string', 'group' => 'social', 'label' => 'LinkedIn URL', 'sort_order' => 4],

            // Maps
            ['key' => 'maps.embed_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124240.74800137316!2d123.70017!3d13.13906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a101fbcd5b4e5f%3A0x2bb1f0b2c0e0f0f0!2sLegazpi%20City%2C%20Albay!5e0!3m2!1sen!2sph!4v1700000000000', 'type' => 'text', 'group' => 'maps', 'label' => 'Google Maps Embed URL', 'sort_order' => 1],

            // About
            ['key' => 'about.mission', 'value' => 'To provide world-class maritime education and training that meets international standards, producing competent, disciplined, and highly skilled seafarers who contribute to the global maritime industry.', 'type' => 'text', 'group' => 'about', 'label' => 'Mission Statement', 'sort_order' => 1],
            ['key' => 'about.vision', 'value' => 'To be the leading maritime training and assessment center in the Bicol Region, recognized for excellence in seafarer education, innovation in training methodologies, and unwavering commitment to maritime safety and professionalism.', 'type' => 'text', 'group' => 'about', 'label' => 'Vision Statement', 'sort_order' => 2],
            ['key' => 'about.story_1', 'value' => 'Founded in {city}, {province}, {name} has grown to become one of the leading maritime training institutions in the Bicol Region.', 'type' => 'text', 'group' => 'about', 'label' => 'Story - Paragraph 1 (supports {city}, {province}, {name} placeholders)', 'sort_order' => 3],
            ['key' => 'about.story_2', 'value' => 'With a commitment to international standards and the safety of seafarers worldwide, we provide STCW-compliant training programs that meet the requirements of maritime industry stakeholders.', 'type' => 'text', 'group' => 'about', 'label' => 'Story - Paragraph 2', 'sort_order' => 4],
            ['key' => 'about.story_3', 'value' => 'Our modern facilities, experienced instructors, and comprehensive curriculum have produced thousands of competent seafarers serving on vessels across the world\'s oceans.', 'type' => 'text', 'group' => 'about', 'label' => 'Story - Paragraph 3', 'sort_order' => 5],

            // Stats
            ['key' => 'stats.years_excellence', 'value' => '15', 'type' => 'int', 'group' => 'stats', 'label' => 'Years of Excellence', 'sort_order' => 1],

            // SEO
            ['key' => 'seo.site_name', 'value' => 'SEATECH Maritime Training', 'type' => 'string', 'group' => 'seo', 'label' => 'SEO Site Name', 'sort_order' => 1],
            ['key' => 'seo.default_description', 'value' => 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol', 'type' => 'text', 'group' => 'seo', 'label' => 'Default Meta Description', 'sort_order' => 2],
            ['key' => 'seo.default_keywords', 'value' => 'maritime training, MARINA accredited, Bicol, Legazpi, seafarer training, STCW', 'type' => 'string', 'group' => 'seo', 'label' => 'Default Meta Keywords', 'sort_order' => 3],
        ];

        $now = now();
        foreach ($defaults as $row) {
            DB::table('site_settings')->insert(array_merge($row, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
