<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERSONAL PLANS
        Plan::updateOrCreate(
            ['slug' => 'personal-free'],
            [
                'name' => 'Personal Free',
                'category' => 'personal',
                'tier' => 'free',
                'description' => 'Paket standar untuk pencatatan harian dasar.',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'is_popular' => false,
                'is_active' => true,
                'display_order' => 1,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'personal-low'],
            [
                'name' => 'Personal Low',
                'category' => 'personal',
                'tier' => 'low',
                'description' => 'Upgrade untuk manajemen anggaran lebih baik.',
                'price_monthly' => 15000,
                'price_yearly' => 144000,
                'is_popular' => false,
                'is_active' => true,
                'display_order' => 2,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'personal-mid'],
            [
                'name' => 'Personal Mid',
                'category' => 'personal',
                'tier' => 'mid',
                'description' => 'Terbaik untuk individu dengan target finansial aktif.',
                'price_monthly' => 30000,
                'price_yearly' => 288000,
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 3,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'personal-high'],
            [
                'name' => 'Personal High',
                'category' => 'personal',
                'tier' => 'high',
                'description' => 'Solusi lengkap untuk investor & profesional.',
                'price_monthly' => 50000,
                'price_yearly' => 480000,
                'is_popular' => false,
                'is_active' => true,
                'display_order' => 4,
            ]
        );

        // FAMILY PLANS
        Plan::updateOrCreate(
            ['slug' => 'family-low'],
            [
                'name' => 'Family Low',
                'category' => 'family',
                'tier' => 'low',
                'description' => 'Mulai kelola keuangan keluarga bersama (Maks 5 Anggota).',
                'price_monthly' => 39000,
                'price_yearly' => 374400,
                'is_popular' => false,
                'is_active' => true,
                'display_order' => 5,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'family-mid'],
            [
                'name' => 'Family Mid',
                'category' => 'family',
                'tier' => 'mid',
                'description' => 'Untuk keluarga lebih besar dengan fitur lebih lengkap (Maks 10 Anggota).',
                'price_monthly' => 69000,
                'price_yearly' => 662400,
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 6,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'family-high'],
            [
                'name' => 'Family High',
                'category' => 'family',
                'tier' => 'high',
                'description' => 'Premium tanpa batas untuk seluruh keluarga (Anggota Unlimited).',
                'price_monthly' => 99000,
                'price_yearly' => 950400,
                'is_popular' => false,
                'is_active' => true,
                'display_order' => 7,
            ]
        );

        // BUSINESS PLAN
        Plan::updateOrCreate(
            ['slug' => 'business'],
            [
                'name' => 'Business',
                'category' => 'business',
                'tier' => 'high',
                'description' => 'Kelola arus kas perusahaan, aset kantor, dan pembukuan awal startup Anda dengan satu platform terintegrasi.',
                'price_monthly' => 550000,
                'price_yearly' => 5280000,
                'is_popular' => false,
                'is_active' => true,
                'display_order' => 8,
            ]
        );
    }
}
