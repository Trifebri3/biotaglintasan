<?php

namespace Database\Seeders;

use App\Models\Adoption;
use App\Models\AuditLog;
use App\Models\EmissionEntry;
use App\Models\EmissionFactor;
use App\Models\GreenAction;
use App\Models\QRCode;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\SchoolMembership;
use App\Models\Tree;
use App\Models\TreeLocation;
use App\Models\TreeObservation;
use App\Models\User;
use App\Models\UserActionLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // 1. Create Users for each Role
        $superAdmin = User::create([
            'name' => 'BioTag Platform Admin',
            'email' => 'superadmin@biotag.org',
            'password' => $defaultPassword,
            'role' => 'biotag_super_admin',
            'status' => 'active',
            'phone' => '081299990001',
        ]);

        $lintasanAdmin = User::create([
            'name' => 'Koordinator Yayasan Lintasan',
            'email' => 'admin@lintasan.org',
            'password' => $defaultPassword,
            'role' => 'lintasan_admin',
            'status' => 'active',
            'phone' => '081299990002',
        ]);

        $officer = User::create([
            'name' => 'Pak Hendra (Pengawas Pohon BioTag)',
            'email' => 'petugas.lapangan@biotag.org',
            'password' => $defaultPassword,
            'role' => 'officer',
            'status' => 'active',
            'phone' => '081299990008',
        ]);

        $teacher = User::create([
            'name' => 'Ibu Dewi Sartika (Guru)',
            'email' => 'guru.dewi@sekolah.sch.id',
            'password' => $defaultPassword,
            'role' => 'school_admin',
            'status' => 'active',
            'phone' => '081299990003',
        ]);

        // Keluarga Andi - Matching Concept Image 1
        $keluargaAndi = User::create([
            'name' => 'Keluarga Andi',
            'email' => 'keluarga.andi@lintasan.id',
            'password' => $defaultPassword,
            'role' => 'user',
            'status' => 'active',
            'phone' => '081299990004',
        ]);

        $studentPending = User::create([
            'name' => 'Ani Lestari (Siswa Baru)',
            'email' => 'ani.pending@lintasan.id',
            'password' => $defaultPassword,
            'role' => 'user',
            'status' => 'pending_verification',
            'phone' => '081299990005',
        ]);

        // 2. Schools and Classes
        $school1 = School::create([
            'name' => 'SD Percobaan Hijau Menteng',
            'code' => 'SCH-JKT-01',
            'address' => 'Jl. Menteng Asri No. 12, Jakarta Pusat',
            'city' => 'Jakarta Pusat',
            'is_active' => true,
        ]);

        $school2 = School::create([
            'name' => 'SMP Alam Nusantara Cilandak',
            'code' => 'SCH-JKT-02',
            'address' => 'Jl. Cilandak Hijau No. 45, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
            'is_active' => true,
        ]);

        $class4A = SchoolClass::create([
            'school_id' => $school1->id,
            'name' => 'Kelas 4A - Alam Lestari',
            'grade_level' => '4',
            'academic_year' => '2026/2027',
        ]);

        $class5B = SchoolClass::create([
            'school_id' => $school1->id,
            'name' => 'Kelas 5B - Tunas Hijau',
            'grade_level' => '5',
            'academic_year' => '2026/2027',
        ]);

        // Memberships
        SchoolMembership::create([
            'school_id' => $school1->id,
            'school_class_id' => $class4A->id,
            'user_id' => $teacher->id,
            'role' => 'teacher',
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        SchoolMembership::create([
            'school_id' => $school1->id,
            'school_class_id' => $class4A->id,
            'user_id' => $keluargaAndi->id,
            'role' => 'student',
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        SchoolMembership::create([
            'school_id' => $school1->id,
            'school_class_id' => $class4A->id,
            'user_id' => $studentPending->id,
            'role' => 'student',
            'status' => 'pending',
            'verified_at' => null,
        ]);

        // 3. Trees, Locations, QR Codes
        $treesData = [
            [
                'code' => 'TR-2026-001',
                'species' => 'Samanea saman',
                'common' => 'Pohon Trembesi Rindang',
                'desc' => 'Pohon peneduh berdaun lebat dengan daya serap karbon tinggi di area taman sekolah.',
                'planted' => '2025-01-15',
                'status' => 'adopted',
                'co2' => 28.50,
                'lat' => -6.195230,
                'lng' => 106.832410,
                'pub_lat' => -6.195000,
                'pub_lng' => 106.832000,
                'address' => 'Taman Halaman Depan SD Percobaan Hijau Menteng',
                'token' => 'qr_trembesi_menteng_2026',
            ],
            [
                'code' => 'TR-2026-002',
                'species' => 'Mangifera indica',
                'common' => 'Pohon Mangga Golek',
                'desc' => 'Pohon buah produktif ditanam siswa dalam program Hari Bumi 2025.',
                'planted' => '2025-04-22',
                'status' => 'adopted',
                'co2' => 22.00,
                'lat' => -6.195410,
                'lng' => 106.832850,
                'pub_lat' => -6.195000,
                'pub_lng' => 106.833000,
                'address' => 'Kebun Belajar Siswa Sayap Timur',
                'token' => 'qr_mangga_golek_2026',
            ],
            [
                'code' => 'TR-2026-003',
                'species' => 'Handroanthus chrysotrichus',
                'common' => 'Pohon Tabebuya Kuning',
                'desc' => 'Pohon berbunga kuning cantik menambah asri lingkungan pedestrian sekolah.',
                'planted' => '2025-08-17',
                'status' => 'verified',
                'co2' => 19.80,
                'lat' => -6.291500,
                'lng' => 106.804200,
                'pub_lat' => -6.291000,
                'pub_lng' => 106.804000,
                'address' => 'Kawasan Terbuka Hijau Cilandak',
                'token' => 'qr_tabebuya_kuning_2026',
            ],
            [
                'code' => 'TR-2026-004',
                'species' => 'Terminalia mantaly',
                'common' => 'Ketapang Kencana Bertingkat',
                'desc' => 'Pohon bertajuk payung bertingkat pelindung area interaksi anak.',
                'planted' => '2026-02-10',
                'status' => 'verified',
                'co2' => 21.20,
                'lat' => -6.291850,
                'lng' => 106.804600,
                'pub_lat' => -6.292000,
                'pub_lng' => 106.805000,
                'address' => 'Plaza Komunitas Cilandak',
                'token' => 'qr_ketapang_kencana_2026',
            ],
            [
                'code' => 'TR-2026-005',
                'species' => 'Syzygium aqueum',
                'common' => 'Pohon Jambu Air Madu',
                'desc' => 'Pohon buah pekarangan keluarga dengan perawatan berkala siswa.',
                'planted' => '2026-03-01',
                'status' => 'verified',
                'co2' => 18.50,
                'lat' => -6.196100,
                'lng' => 106.831900,
                'pub_lat' => -6.196000,
                'pub_lng' => 106.832000,
                'address' => 'Taman Herbal & Buah Sekolah Menteng',
                'token' => 'qr_jambu_air_2026',
            ],
        ];

        foreach ($treesData as $t) {
            $tree = Tree::create([
                'tree_code' => $t['code'],
                'species_name' => $t['species'],
                'common_name' => $t['common'],
                'description' => $t['desc'],
                'planted_date' => $t['planted'],
                'status' => $t['status'],
                'co2_absorption_kg_year' => $t['co2'],
            ]);

            TreeLocation::create([
                'tree_id' => $tree->id,
                'latitude' => $t['lat'],
                'longitude' => $t['lng'],
                'public_latitude' => $t['pub_lat'],
                'public_longitude' => $t['pub_lng'],
                'address_description' => $t['address'],
                'visibility' => 'generalized',
            ]);

            QRCode::create([
                'tree_id' => $tree->id,
                'token' => $t['token'],
                'status' => $t['status'] === 'adopted' ? 'used' : 'active',
                'created_by' => $superAdmin->id,
            ]);

            // Jika status adopted, buat record adopsi untuk Keluarga Andi
            if ($t['status'] === 'adopted') {
                Adoption::create([
                    'tree_id' => $tree->id,
                    'user_id' => $keluargaAndi->id,
                    'adopted_at' => now()->subDays(20),
                    'status' => 'active',
                    'notes' => 'Diadopsi bersama Keluarga Andi dalam Gerakan Sekolah Hijau.',
                ]);

                // Create realistic observation reports by Officer Pak Hendra WITH PHOTO
                TreeObservation::create([
                    'tree_id' => $tree->id,
                    'observed_by' => $officer->id,
                    'condition_status' => 'thriving',
                    'notes' => 'Inspeksi rutin Pengawas Lapangan: Kondisi pohon sangat sehat, daun lebat menghijau, batang kokoh dengan patok penopang terawat baik.',
                    'photo_url' => '/images/tree-sample-1.jpg',
                    'observed_at' => now()->subDays(2),
                ]);

                TreeObservation::create([
                    'tree_id' => $tree->id,
                    'observed_by' => $officer->id,
                    'condition_status' => 'healthy',
                    'notes' => 'Pemupukan organik berkala dan penyiraman telah dilaksanakan oleh tim kebun sekolah.',
                    'photo_url' => '/images/tree-sample-1.jpg',
                    'observed_at' => now()->subDays(10),
                ]);
            }
        }

        // 4. Green Actions - matching Image 1
        $greenActions = [
            ['title' => 'Hemat Listrik', 'category' => 'energy', 'desc' => 'Matikan lampu dan AC di ruangan yang sedang kosong.', 'points' => 10],
            ['title' => 'Kurangi Perjalanan', 'category' => 'travel', 'desc' => 'Pilih jalan kaki, sepeda, atau gabungkan rute perjalanan.', 'points' => 15],
            ['title' => 'Kurangi Sampah', 'category' => 'waste', 'desc' => 'Bawa botol minum sendiri (tumbler) dan hindari plastik sekali pakai.', 'points' => 10],
            ['title' => 'Hemat Air', 'category' => 'water', 'desc' => 'Tutup kran air saat menyikat gigi dan gunakan air secukupnya.', 'points' => 10],
            ['title' => 'Aksi Lainnya', 'category' => 'tree_care', 'desc' => 'Siram tanaman pekarangan dan rawat pohon adopsi keluarga.', 'points' => 20],
        ];

        foreach ($greenActions as $ga) {
            $action = GreenAction::create([
                'title' => $ga['title'],
                'category' => $ga['category'],
                'description' => $ga['desc'],
                'impact_points' => $ga['points'],
                'is_active' => true,
            ]);

            // Mark Hemat Listrik as done today for Keluarga Andi
            if ($ga['title'] === 'Hemat Listrik') {
                UserActionLog::create([
                    'user_id' => $keluargaAndi->id,
                    'green_action_id' => $action->id,
                    'completed_at' => now(),
                    'notes' => 'Mematikan lampu ruang tamu dan AC pagi hari.',
                ]);
            }
        }

        // 5. Emission Factors
        $factors = [
            [
                'category' => 'electricity',
                'unit' => 'kWh',
                'factor' => 0.7850,
                'version' => 'v1.0-2026',
                'source' => 'Faktor Emisi Jaringan Listrik PLN Jamali (Kementerian ESDM 2026)',
                'effective_date' => '2026-01-01',
            ],
            [
                'category' => 'transport_gasoline',
                'unit' => 'liter',
                'factor' => 2.3100,
                'version' => 'v1.0-2026',
                'source' => 'Pedoman Inventarisasi GRK Nasional (KLHK / IPCC 2026)',
                'effective_date' => '2026-01-01',
            ],
            [
                'category' => 'transport_motorcycle',
                'unit' => 'km',
                'factor' => 0.0820,
                'version' => 'v1.0-2026',
                'source' => 'Rata-rata Emisi Sepeda Motor 110-150cc Indonesia 2026',
                'effective_date' => '2026-01-01',
            ],
            [
                'category' => 'cooking_lpg',
                'unit' => 'kg',
                'factor' => 3.0000,
                'version' => 'v1.0-2026',
                'source' => 'Faktor Emisi Pembakaran LPG Rumah Tangga IPCC',
                'effective_date' => '2026-01-01',
            ],
        ];

        foreach ($factors as $f) {
            EmissionFactor::create([
                'category' => $f['category'],
                'unit' => $f['unit'],
                'co2e_factor' => $f['factor'],
                'version' => $f['version'],
                'source_reference' => $f['source'],
                'effective_date' => $f['effective_date'],
            ]);
        }

        // Seed Keluarga Andi's exact emissions: Total 3,82 TON = 3820 kg CO2e
        // 48% Listrik = 1833.6 kg
        // 32% Mobil = 1222.4 kg
        // 12% Motor = 458.4 kg
        // 8% LPG = 305.6 kg
        $factorListrik = EmissionFactor::where('category', 'electricity')->first();
        $factorMobil = EmissionFactor::where('category', 'transport_gasoline')->first();
        $factorMotor = EmissionFactor::where('category', 'transport_motorcycle')->first();
        $factorLpg = EmissionFactor::where('category', 'cooking_lpg')->first();

        EmissionEntry::create([
            'user_id' => $keluargaAndi->id,
            'emission_factor_id' => $factorListrik?->id,
            'category' => 'Listrik',
            'activity_value' => round(1833.6 / 0.7850, 2),
            'unit' => 'kWh',
            'calculated_co2e' => 1833.60,
            'period_start' => now()->subMonths(12)->toDateString(),
            'period_end' => now()->toDateString(),
            'notes' => 'Pemakaian listrik pendingin dan peralatan rumah.',
        ]);

        EmissionEntry::create([
            'user_id' => $keluargaAndi->id,
            'emission_factor_id' => $factorMobil?->id,
            'category' => 'Transportasi (Mobil)',
            'activity_value' => round(1222.4 / 2.3100, 2),
            'unit' => 'liter',
            'calculated_co2e' => 1222.40,
            'period_start' => now()->subMonths(12)->toDateString(),
            'period_end' => now()->toDateString(),
            'notes' => 'Perjalanan kerja dan belanja keluarga.',
        ]);

        EmissionEntry::create([
            'user_id' => $keluargaAndi->id,
            'emission_factor_id' => $factorMotor?->id,
            'category' => 'Kendaraan (Motor)',
            'activity_value' => round(458.4 / 0.0820, 2),
            'unit' => 'km',
            'calculated_co2e' => 458.40,
            'period_start' => now()->subMonths(12)->toDateString(),
            'period_end' => now()->toDateString(),
            'notes' => 'Perjalanan antar jemput harian.',
        ]);

        EmissionEntry::create([
            'user_id' => $keluargaAndi->id,
            'emission_factor_id' => $factorLpg?->id,
            'category' => 'LPG',
            'activity_value' => round(305.6 / 3.00, 2),
            'unit' => 'kg',
            'calculated_co2e' => 305.60,
            'period_start' => now()->subMonths(12)->toDateString(),
            'period_end' => now()->toDateString(),
            'notes' => 'Penggunaan gas masak dapur.',
        ]);

        // Audit Log Initial Record
        AuditLog::create([
            'user_id' => $superAdmin->id,
            'action' => 'system_initialized',
            'entity_type' => 'Platform',
            'entity_id' => null,
            'new_values' => ['message' => 'Platform Lintasan x BioTag v1.0 initialized with officer role and family Andi profile.'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder Script',
        ]);
    }
}
