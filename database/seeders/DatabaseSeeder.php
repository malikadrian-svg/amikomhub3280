<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
 public function run(): void
 {
 // 1. Akun Admin Utama
 User::create([
 'name' => 'Admin Amikom',
 'email' => 'admin@amikom.ac.id',
 'password' => bcrypt('password'),
 'role' => 'admin'
 ]);
 // 2. Insert Kategori Event
 $catSeminar = \App\Models\Category::create([
 'name' => 'Seminar IT',
 'slug' => 'seminar-it',
 ]);
 $catEntertainment = \App\Models\Category::create([
 'name' => 'Entertainment',
 'slug' => 'entertainment',
 ]);
 $catWorkshop = \App\Models\Category::create([
 'name' => 'Workshop & Training',
 'slug' => 'workshop-training',
 ]);
 $catEsport = \App\Models\Category::create([
 'name' => 'E-Sport',
 'slug' => 'e-sport',
 ]);
 $catKompetisi = \App\Models\Category::create([
 'name' => 'Kompetisi & Lomba',
 'slug' => 'kompetisi-lomba',
 ]);
 $catKomunitas = \App\Models\Category::create([
 'name' => 'Komunitas & Sosial',
 'slug' => 'komunitas-sosial',
 ]);

 // 3. Insert Sampel Events (6 event bervariatif)
 \App\Models\Event::create([
 'category_id' => $catWorkshop->id,
 'title' => 'UI/UX Masterclass',
 'description' => 'Pelajari prinsip desain UI/UX dari praktisi industri. Dari wireframe hingga prototype interaktif menggunakan Figma.',
 'date' => '2026-05-05 09:00:00',
 'location' => 'Lab Multimedia Lt.3',
 'price' => 35000,
 'stock' => 80,
 'poster_path' => 'posters/event-1.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catEsport->id,
 'title' => 'E-Sport U-Champ 2026',
 'description' => 'Turnamen E-Sport antar universitas se-DIY. Game: Valorant & Mobile Legends. Hadiah total jutaan rupiah!',
 'date' => '2026-06-14 10:00:00',
 'location' => 'GOR Amikom',
 'price' => 25000,
 'stock' => 200,
 'poster_path' => 'posters/event-2.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catEntertainment->id,
 'title' => 'Jazz Night 2025',
 'description' => 'Nikmati malam yang indah dengan alunan musik jazz dari musisi lokal berbakat. Free snack & drinks!',
 'date' => '2026-05-10 19:00:00',
 'location' => 'Amikom Baru',
 'price' => 50000,
 'stock' => 100,
 'poster_path' => 'posters/event-3.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catSeminar->id,
 'title' => 'AI Summit & Expo 2026',
 'description' => 'Jelajahi tren terkini dalam bidang Artificial Intelligence bersama para pakar dari Google dan Microsoft.',
 'date' => '2026-05-01 13:00:00',
 'location' => 'Ruang Cinema',
 'price' => 45000,
 'stock' => 150,
 'poster_path' => 'posters/event-4.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catWorkshop->id,
 'title' => 'Cybersecurity Bootcamp',
 'description' => 'Workshop intensif keamanan siber: ethical hacking, penetration testing, dan CTF challenge untuk pemula hingga menengah.',
 'date' => '2026-07-20 08:30:00',
 'location' => 'Lab Jaringan Lt.2',
 'price' => 40000,
 'stock' => 60,
 'poster_path' => 'posters/event-5.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catEntertainment->id,
 'title' => 'Stand-Up Comedy Night',
 'description' => 'Malam penuh tawa bersama komika-komika kampus! Open mic untuk mahasiswa yang berani tampil.',
 'date' => '2026-08-02 19:30:00',
 'location' => 'Auditorium Amikom',
 'price' => 20000,
 'stock' => 120,
 'poster_path' => 'posters/event-6.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catKompetisi->id,
 'title' => 'Hackathon Amikom 2026',
 'description' => 'Kompetisi coding marathon 24 jam! Bangun solusi inovatif untuk permasalahan nyata. Terbuka untuk semua jurusan.',
 'date' => '2026-09-10 08:00:00',
 'location' => 'Co-Working Space Lt.4',
 'price' => 30000,
 'stock' => 100,
 'poster_path' => 'posters/event-7.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catSeminar->id,
 'title' => 'Data Science Seminar',
 'description' => 'Kupas tuntas dunia Data Science, Machine Learning, dan Big Data bersama praktisi dari Tokopedia dan Gojek.',
 'date' => '2026-06-25 13:00:00',
 'location' => 'Ruang Cinema',
 'price' => 40000,
 'stock' => 130,
 'poster_path' => 'posters/event-8.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catKompetisi->id,
 'title' => 'Film Festival Mahasiswa',
 'description' => 'Lomba film pendek bertema "Digital Culture". Unjuk kreativitasmu dalam sinematografi dan storytelling!',
 'date' => '2026-10-05 09:00:00',
 'location' => 'Auditorium Amikom',
 'price' => 15000,
 'stock' => 250,
 'poster_path' => 'posters/event-9.png',
 ]);
 \App\Models\Event::create([
 'category_id' => $catKomunitas->id,
 'title' => 'Bakti Sosial Kampus',
 'description' => 'Kegiatan bakti sosial bersama warga sekitar kampus. Donasi buku, alat tulis, dan pengajaran IT dasar untuk anak-anak.',
 'date' => '2026-07-12 07:30:00',
 'location' => 'Balai Desa Condongcatur',
 'price' => 0,
 'stock' => 50,
 'poster_path' => 'posters/event-10.png',
 ]);
 }
}