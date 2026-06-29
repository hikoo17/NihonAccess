<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Basic Online',
                'slug' => 'basic',
                'type' => 'online',
                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                'badge' => 'Online Starter',
                'format' => 'Belajar Mandiri',
                'description' => 'Belajar melalui video pembelajaran berkualitas tinggi dengan akses selamanya. Cocok untuk yang mandiri.',
                'level' => 'Pemula',
                'price' => 99000,
                'price_note' => 'Sekali bayar untuk akses 1 bulan',
                'duration_days' => 30,
                'highlights' => [
                    ['label' => 'Materi', 'value' => 'Huruf & kosakata'],
                    ['label' => 'Akses', 'value' => 'Web learning'],
                    ['label' => 'Latihan', 'value' => '500+ soal'],
                ],
                'modules' => [
                    ['name' => 'Pengenalan Hiragana', 'description' => 'Belajar 46 huruf dasar dengan audio native speaker'],
                    ['name' => 'Pengenalan Katakana', 'description' => 'Pelajari 46 huruf katakana dengan contoh penggunaan'],
                    ['name' => 'Kosakata Dasar', 'description' => 'Kumpulan 300+ kata umum dalam bahasa Jepang'],
                ],
                'suitable_for' => [
                    'Siswa yang baru mulai dari nol dan ingin mengenal huruf Jepang.',
                    'Pembelajar mandiri yang butuh materi ringkas dan bisa diulang kapan saja.',
                    'Peserta yang ingin mencoba kursus sebelum masuk ke program mentor.',
                    'Orang sibuk yang membutuhkan jadwal belajar fleksibel.',
                ],
                'features' => [
                    'Akses video Hiragana & Katakana lengkap',
                    'Materi PDF interaktif untuk latihan',
                    'Bank soal latihan 500+ pertanyaan',
                    'Akses materi selama 1 bulan',
                    'Grup komunitas belajar via Discord',
                ],
            ],
            [
                'name' => 'Paket Premium Online',
                'slug' => 'premium',
                'type' => 'online',
                'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                'badge' => 'Online Mentoring',
                'format' => 'Zoom + Materi Web',
                'description' => 'Program intensif dengan mentor melalui Zoom. Cocok untuk yang ingin belajar secara langsung.',
                'level' => 'N5 Dasar',
                'price' => 199000,
                'price_note' => 'Termasuk sesi live mingguan',
                'duration_days' => 90,
                'highlights' => [
                    ['label' => 'Mentoring', 'value' => 'Live Zoom'],
                    ['label' => 'Target', 'value' => 'JLPT N5'],
                    ['label' => 'Akses', 'value' => '3 bulan'],
                ],
                'modules' => [
                    ['name' => 'Grammar Dasar', 'description' => 'Pola kalimat dasar hingga lanjutan JLPT N5'],
                    ['name' => 'Kanji Pilihan', 'description' => '200 kanji dasar yang sering digunakan'],
                    ['name' => 'Simulasi Ujian', 'description' => 'Tryout rutin untuk persiapan JLPT N5'],
                ],
                'suitable_for' => [
                    'Peserta yang ingin belajar dengan arahan mentor, bukan hanya video.',
                    'Siswa yang menargetkan kemampuan setara JLPT N5.',
                    'Pembelajar yang membutuhkan jadwal dan progres yang lebih terstruktur.',
                    'Orang yang ingin rutin bertanya langsung saat menemukan materi sulit.',
                ],
                'features' => [
                    'Semua fitur Online Course',
                    'Kanji dasar 200+ karakter',
                    'Grammar JLPT N5 lengkap',
                    'Latihan soal JLPT N5 siap ujian',
                    'Akses materi selama 3 bulan',
                    'Sesi tanya jawab mingguan via Zoom',
                ],
            ],
            [
                'name' => 'Paket Private Online',
                'slug' => 'private',
                'type' => 'online',
                'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 10v-2a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75',
                'badge' => '1-on-1 Online',
                'format' => 'Kelas Private',
                'description' => 'Kelas online personal dengan tutor untuk menyesuaikan tempo, target, dan fokus belajar peserta.',
                'level' => 'Pemula - N5',
                'price' => 499000,
                'price_note' => 'Program personal dengan jadwal fleksibel',
                'duration_days' => 180,
                'highlights' => [
                    ['label' => 'Kelas', 'value' => 'Private Zoom'],
                    ['label' => 'Jadwal', 'value' => 'Fleksibel'],
                    ['label' => 'Review', 'value' => 'Personal'],
                ],
                'modules' => [
                    ['name' => 'Konsultasi Pribadi', 'description' => 'Sesi belajar individu dengan tutor berpengalaman'],
                    ['name' => 'Review Materi', 'description' => 'Evaluasi dan perbaikan pada materi yang sulit'],
                    ['name' => 'Persiapan Ujian', 'description' => 'Strategi khusus untuk menghadapi JLPT'],
                ],
                'suitable_for' => [
                    'Peserta yang ingin perhatian penuh dari tutor.',
                    'Pembelajar dengan target khusus seperti ujian, kerja, atau wawancara.',
                    'Siswa yang membutuhkan jadwal belajar lebih fleksibel.',
                    'Orang yang ingin progresnya dievaluasi secara personal.',
                ],
                'features' => [
                    'Semua fitur Bootcamp',
                    'Kelas private via Zoom',
                    'Konsultasi belajar 1-on-1',
                    'Akses materi selama 6 bulan',
                    'Jadwal fleksibel sesuai keinginan',
                ],
            ],
            [
                'name' => 'Paket Basic On-Site',
                'slug' => 'reguler',
                'type' => 'onsite',
                'icon' => 'M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5',
                'badge' => 'Tatap Muka',
                'format' => 'Kelas Kelompok',
                'description' => 'Belajar interaktif langsung tatap muka di ruang kelas bersama teman kelompok.',
                'level' => 'Pemula',
                'price' => 350000,
                'price_note' => 'Termasuk modul cetak dan evaluasi',
                'duration_days' => 30,
                'highlights' => [
                    ['label' => 'Pertemuan', 'value' => '2x/minggu'],
                    ['label' => 'Modul', 'value' => 'Cetak'],
                    ['label' => 'Evaluasi', 'value' => 'Bulanan'],
                ],
                'modules' => [
                    ['name' => 'Kelas Tatap Muka', 'description' => 'Belajar langsung di tempat dengan metode interaktif'],
                    ['name' => 'Praktik Langsung', 'description' => 'Pelajaran praktik dengan tutor profesional'],
                    ['name' => 'Evaluasi Rutin', 'description' => 'Penilaian mingguan untuk melacak kemajuan'],
                ],
                'suitable_for' => [
                    'Siswa yang lebih nyaman belajar langsung di kelas.',
                    'Peserta yang suka diskusi bersama teman kelompok.',
                    'Pembelajar yang membutuhkan rutinitas pertemuan tetap.',
                    'Orang yang ingin latihan pelafalan secara langsung.',
                ],
                'features' => [
                    'Kelas tatap muka (seminggu 2x)',
                    'Modul cetak dan merchandise',
                    'Grup diskusi offline',
                    'Evaluasi bulanan',
                ],
            ],
            [
                'name' => 'Paket Premium On-Site',
                'slug' => 'intensive',
                'type' => 'onsite',
                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'badge' => 'Kelas Intensif',
                'format' => 'Tatap Muka Harian',
                'description' => 'Akselerasi materi terstruktur padat dengan bimbingan harian yang dipandu penuh.',
                'level' => 'N5 Terarah',
                'price' => 599000,
                'price_note' => 'Untuk progres cepat dan jadwal padat',
                'duration_days' => 30,
                'highlights' => [
                    ['label' => 'Pertemuan', 'value' => 'Setiap hari'],
                    ['label' => 'Fasilitas', 'value' => 'Ruang belajar'],
                    ['label' => 'Tryout', 'value' => 'Offline'],
                ],
                'modules' => [
                    ['name' => 'Belajar Harian', 'description' => 'Pertemuan rutin 5x seminggu'],
                    ['name' => 'Simulasi Ujian', 'description' => 'Tryout intensif selama program'],
                    ['name' => 'Ruang Belajar', 'description' => 'Fasilitas belajar gratis selama program'],
                ],
                'suitable_for' => [
                    'Peserta yang ingin mengejar target dalam waktu singkat.',
                    'Siswa yang siap belajar dengan ritme intensif.',
                    'Pembelajar yang membutuhkan lingkungan belajar fokus.',
                    'Orang yang ingin persiapan lebih serius untuk ujian dasar.',
                ],
                'features' => [
                    'Semua fitur Reguler On-Site',
                    'Kelas tatap muka setiap hari',
                    'Free-flow coffee & ruang belajar',
                    'Simulasi JLPT offline',
                    'Prioritas jadwal',
                ],
            ],
            [
                'name' => 'Paket Private Corporate',
                'slug' => 'corporate',
                'type' => 'onsite',
                'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'badge' => 'Corporate Training',
                'format' => 'Program Custom',
                'description' => 'Pelatihan kurikulum khusus bisnis skala korporat yang jadwalnya fleksibel.',
                'level' => 'Sesuai kebutuhan',
                'price' => 0,
                'price_note' => 'Harga menyesuaikan jumlah peserta dan kebutuhan',
                'duration_days' => 0,
                'highlights' => [
                    ['label' => 'Kurikulum', 'value' => 'Custom'],
                    ['label' => 'Lokasi', 'value' => 'Fleksibel'],
                    ['label' => 'Sertifikat', 'value' => 'Resmi'],
                ],
                'modules' => [
                    ['name' => 'Kurikulum Custom', 'description' => 'Materi disesuaikan dengan kebutuhan korporat'],
                    ['name' => 'Lokasi Fleksibel', 'description' => 'Bisa di kantor atau kami datang ke lokasi'],
                    ['name' => 'Sertifikat Resmi', 'description' => 'Sertifikat pelatihan untuk peserta'],
                ],
                'suitable_for' => [
                    'Perusahaan yang membutuhkan pelatihan bahasa Jepang untuk tim.',
                    'Universitas atau komunitas yang ingin program belajar khusus.',
                    'Tim HR yang membutuhkan jadwal dan materi fleksibel.',
                    'Organisasi yang ingin pelatihan dengan laporan progres peserta.',
                ],
                'features' => [
                    'Jadwal fleksibel',
                    'Lokasi bisa disesuaikan',
                    'Kurikulum khusus bisnis',
                    'Sertifikat resmi pelatihan',
                ],
            ],
        ];

        foreach ($packages as $data) {
            $features = $data['features'];
            unset($data['features']);

            $package = Package::create(array_merge($data, ['is_active' => true]));

            foreach ($features as $index => $name) {
                PackageFeature::create([
                    'package_id' => $package->id,
                    'name' => $name,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
