<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERSONAL FREE FEATURES
        $personalFree = Plan::where('slug', 'personal-free')->first();
        if ($personalFree) {
            $this->addFeatures($personalFree, [
                ['name' => 'Pencatatan Pemasukan & Pengeluaran', 'icon' => 'bi-wallet2'],
                ['name' => 'Maks. 1 Rekening', 'icon' => 'bi-bank'],
                ['name' => 'Laporan Mingguan', 'icon' => 'bi-graph-up'],
            ]);
        }

        // PERSONAL LOW FEATURES
        $personalLow = Plan::where('slug', 'personal-low')->first();
        if ($personalLow) {
            $this->addFeatures($personalLow, [
                ['name' => 'Semua fitur paket Free', 'icon' => 'bi-check-all'],
                ['name' => 'Manajemen Anggaran', 'icon' => 'bi-calculator'],
                ['name' => 'Laporan Bulanan', 'icon' => 'bi-file-earmark-pdf'],
                ['name' => 'Maks. 3 Rekening/Dompet', 'icon' => 'bi-wallet2'],
            ]);
        }

        // PERSONAL MID FEATURES
        $personalMid = Plan::where('slug', 'personal-mid')->first();
        if ($personalMid) {
            $this->addFeatures($personalMid, [
                ['name' => 'Semua fitur paket Low', 'icon' => 'bi-check-all'],
                ['name' => 'Target Finansial (Goals)', 'icon' => 'bi-bullseye'],
                ['name' => 'Utang & Piutang', 'icon' => 'bi-arrow-left-right'],
                ['name' => 'Maks. 10 Rekening/Aset', 'icon' => 'bi-bank'],
                ['name' => 'Visualisasi Lanjutan', 'icon' => 'bi-graph-up-arrow'],
            ]);
        }

        // PERSONAL HIGH FEATURES
        $personalHigh = Plan::where('slug', 'personal-high')->first();
        if ($personalHigh) {
            $this->addFeatures($personalHigh, [
                ['name' => 'Semua fitur paket Medium', 'icon' => 'bi-check-all'],
                ['name' => 'Portofolio Investasi', 'icon' => 'bi-graph-up'],
                ['name' => 'Brankas Digital + OCR', 'icon' => 'bi-safe'],
                ['name' => 'Aset Unlimited', 'icon' => 'bi-infinity'],
                ['name' => 'Analisis Wealth Net', 'icon' => 'bi-pie-chart'],
            ]);
        }

        // FAMILY LOW FEATURES
        $familyLow = Plan::where('slug', 'family-low')->first();
        if ($familyLow) {
            $this->addFeatures($familyLow, [
                ['name' => 'Fitur setara Personal High', 'icon' => 'bi-check-all'],
                ['name' => 'Dompet Bersama (Joint Account)', 'icon' => 'bi-people'],
                ['name' => 'Pembagian Tagihan (Split Bill)', 'icon' => 'bi-divide'],
                ['name' => 'Maks. 5 Anggota', 'icon' => 'bi-people-fill'],
            ]);
        }

        // FAMILY MID FEATURES
        $familyMid = Plan::where('slug', 'family-mid')->first();
        if ($familyMid) {
            $this->addFeatures($familyMid, [
                ['name' => 'Semua fitur Family Low', 'icon' => 'bi-check-all'],
                ['name' => 'Role Admin & Anggota', 'icon' => 'bi-shield-check'],
                ['name' => 'Manajemen Aset Keluarga', 'icon' => 'bi-house-heart'],
                ['name' => 'Maks. 10 Anggota', 'icon' => 'bi-people-fill'],
            ]);
        }

        // FAMILY HIGH FEATURES
        $familyHigh = Plan::where('slug', 'family-high')->first();
        if ($familyHigh) {
            $this->addFeatures($familyHigh, [
                ['name' => 'Semua fitur Family Mid', 'icon' => 'bi-check-all'],
                ['name' => 'Brankas Digital Keluarga', 'icon' => 'bi-safe2'],
                ['name' => 'Laporan Wealth Generation', 'icon' => 'bi-graph-up'],
                ['name' => 'Anggota Unlimited', 'icon' => 'bi-infinity'],
            ]);
        }

        // BUSINESS FEATURES
        $business = Plan::where('slug', 'business')->first();
        if ($business) {
            $this->addFeatures($business, [
                ['name' => 'Pemisahan Akun Bisnis', 'icon' => 'bi-briefcase'],
                ['name' => 'Multi-Approval System', 'icon' => 'bi-check-square'],
                ['name' => 'Laba Rugi Otomatis', 'icon' => 'bi-calculator'],
                ['name' => 'Ekspor Data Enterprise', 'icon' => 'bi-download'],
                ['name' => 'Laporan Siap Pajak', 'icon' => 'bi-file-text'],
                ['name' => 'SLA 99.9% GUARANTEED', 'icon' => 'bi-shield-check'],
                ['name' => 'Integrasi Rekening Bank Otomatis', 'icon' => 'bi-bank'],
                ['name' => 'Manajemen Multi-Cabang & Departemen', 'icon' => 'bi-diagram-3'],
                ['name' => 'Prediksi Arus Kas AI (Forecast)', 'icon' => 'bi-cpu'],
                ['name' => 'Sistem Invoice & Tagihan Terjadwal', 'icon' => 'bi-receipt'],
                ['name' => 'Kustomisasi Laporan Keuangan', 'icon' => 'bi-file-earmark-bar-graph'],
                ['name' => 'Akses API & Integrasi Aplikasi', 'icon' => 'bi-code-slash'],
            ]);
        }
    }

    private function addFeatures(Plan $plan, array $features): void
    {
        foreach ($features as $index => $feature) {
            PlanFeature::updateOrCreate(
                [
                    'plan_id' => $plan->id,
                    'feature_name' => $feature['name']
                ],
                [
                    'feature_description' => null,
                    'icon_class' => $feature['icon'] ?? 'bi-check',
                    'display_order' => $index,
                    'is_included' => true,
                ]
            );
        }
    }
}
