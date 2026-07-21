<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\PlatformSetting;
use App\Models\Role;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Platform Settings ──────────────────────────────────────────────
        $this->seedPlatformSettings();

        // ─── 2. Roles & Permissions ────────────────────────────────────────────
        $this->seedRolesAndPermissions();

        // ─── 3. Categories ─────────────────────────────────────────────────────
        $categories = $this->seedCategories();

        // ─── 4. Admin User ─────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@amikomhub.id'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        
        // Attach super_admin role (syncWithoutDetaching = safe to run multiple times)
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        if ($superAdminRole) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        // ─── 5. Organizations ──────────────────────────────────────────────────
        $organizations = $this->seedOrganizations($admin);

        // ─── 6. Events ─────────────────────────────────────────────────────────
        $events = $this->seedEvents($categories, $organizations, $admin);

        // ─── 7. Orders and Reviews ─────────────────────────────────────────────
        $this->seedOrdersAndReviews($events);
    }

    // =========================================================================
    // Seeder Methods
    // =========================================================================

    private function seedPlatformSettings(): void
    {
        $settings = [
            ['key' => 'platform_name',              'value' => 'AmikomHub',    'type' => 'string',  'description' => 'Platform display name'],
            ['key' => 'default_commission_rate',    'value' => '5.00',         'type' => 'decimal', 'description' => 'Default platform commission (%) per transaction'],
            ['key' => 'platform_fee',               'value' => '5000',         'type' => 'integer', 'description' => 'Fixed admin fee per order (IDR)'],
            ['key' => 'require_event_approval',     'value' => 'true',         'type' => 'boolean', 'description' => 'Events must be approved by super admin before publishing'],
            ['key' => 'require_organizer_approval', 'value' => 'true',         'type' => 'boolean', 'description' => 'New organizer registrations require super admin approval'],
            ['key' => 'review_grace_days',          'value' => '1',            'type' => 'integer', 'description' => 'Days after event end before reviews are allowed'],
            ['key' => 'review_edit_days',           'value' => '7',            'type' => 'integer', 'description' => 'Days after submission that a review can be edited'],
            ['key' => 'max_ticket_types_per_event', 'value' => '5',            'type' => 'integer', 'description' => 'Maximum ticket types an organizer can create per event'],
            ['key' => 'support_email',              'value' => 'support@amikomhub.id', 'type' => 'string', 'description' => 'Platform support email address'],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    private function seedRolesAndPermissions(): void
    {
        // --- Roles ---
        $roles = [
            ['name' => 'Super Admin',        'slug' => 'super_admin',        'description' => 'Full platform access. Manages organizations, events, and platform settings.'],
            ['name' => 'Organizer Owner',    'slug' => 'organizer_owner',    'description' => 'Full access within their organization. Can manage members, events, and settings.'],
            ['name' => 'Organizer Manager',  'slug' => 'organizer_manager',  'description' => 'Manage events, orders, and analytics within their organization.'],
            ['name' => 'Organizer Staff',    'slug' => 'organizer_staff',    'description' => 'View orders, scan tickets. Cannot modify events.'],
            ['name' => 'Customer',           'slug' => 'customer',           'description' => 'Default role for public users. Can purchase tickets and write reviews.'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['slug' => $roleData['slug']], [...$roleData, 'is_system' => true]);
        }

        // --- Permissions ---
        $permissions = [
            // Platform-level (super_admin only)
            ['group' => 'platform',     'name' => 'Manage Platform Settings', 'slug' => 'platform.manage'],
            ['group' => 'platform',     'name' => 'View Platform Analytics',  'slug' => 'platform.analytics'],
            ['group' => 'organizers',   'name' => 'Approve Organizers',       'slug' => 'organizers.approve'],
            ['group' => 'organizers',   'name' => 'Suspend Organizers',       'slug' => 'organizers.suspend'],
            ['group' => 'users',        'name' => 'Manage Users',             'slug' => 'users.manage'],
            ['group' => 'categories',   'name' => 'Manage Categories',        'slug' => 'categories.manage'],
            ['group' => 'reviews',      'name' => 'Moderate Reviews',         'slug' => 'reviews.moderate'],
            ['group' => 'events',       'name' => 'Approve Events',           'slug' => 'events.approve'],

            // Organization-level
            ['group' => 'organization', 'name' => 'Manage Organization Settings', 'slug' => 'organization.settings'],
            ['group' => 'organization', 'name' => 'Manage Organization Members',  'slug' => 'organization.members'],
            ['group' => 'events',       'name' => 'Create Events',            'slug' => 'events.create'],
            ['group' => 'events',       'name' => 'Edit Events',              'slug' => 'events.edit'],
            ['group' => 'events',       'name' => 'Delete Events',            'slug' => 'events.delete'],
            ['group' => 'events',       'name' => 'View Events (Org)',        'slug' => 'events.view'],
            ['group' => 'orders',       'name' => 'Manage Orders',            'slug' => 'orders.manage'],
            ['group' => 'orders',       'name' => 'View Orders',              'slug' => 'orders.view'],
            ['group' => 'analytics',    'name' => 'View Analytics',           'slug' => 'analytics.view'],
            ['group' => 'tickets',      'name' => 'Scan Tickets',             'slug' => 'tickets.scan'],
            ['group' => 'reviews',      'name' => 'View Reviews (Org)',       'slug' => 'reviews.view'],

            // Customer
            ['group' => 'tickets',      'name' => 'Purchase Tickets',         'slug' => 'tickets.purchase'],
            ['group' => 'reviews',      'name' => 'Create Reviews',           'slug' => 'reviews.create'],
        ];

        $permissionModels = [];
        foreach ($permissions as $permData) {
            $permissionModels[$permData['slug']] = Permission::firstOrCreate(['slug' => $permData['slug']], $permData);
        }

        // --- Role-Permission Assignments ---
        $rolePermMap = [
            'super_admin' => [
                'platform.manage', 'platform.analytics', 'organizers.approve',
                'organizers.suspend', 'users.manage', 'categories.manage',
                'reviews.moderate', 'events.approve', 'events.view',
                'orders.view', 'analytics.view', 'reviews.view',
            ],
            'organizer_owner' => [
                'organization.settings', 'organization.members',
                'events.create', 'events.edit', 'events.delete', 'events.view',
                'orders.manage', 'orders.view', 'analytics.view',
                'tickets.scan', 'reviews.view',
            ],
            'organizer_manager' => [
                'events.create', 'events.edit', 'events.view',
                'orders.manage', 'orders.view', 'analytics.view',
                'tickets.scan', 'reviews.view',
            ],
            'organizer_staff' => [
                'events.view', 'orders.view', 'tickets.scan', 'reviews.view',
            ],
            'customer' => [
                'tickets.purchase', 'reviews.create',
            ],
        ];

        foreach ($rolePermMap as $roleSlug => $permSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            if (!$role) continue;
            $permIds = collect($permSlugs)
                ->filter(fn ($slug) => isset($permissionModels[$slug]))
                ->map(fn ($slug) => $permissionModels[$slug]->id)
                ->all();
            // Sync instead of attach to avoid duplicate pivot entries
            $role->permissions()->syncWithoutDetaching($permIds);
        }
    }

    private function seedCategories(): \Illuminate\Support\Collection
    {
        $categories = [
            ['name' => 'Musik & Konser',    'slug' => 'musik-konser',       'icon' => '🎵', 'description' => 'Konser, festival musik, dan pertunjukan live.', 'sort_order' => 1],
            ['name' => 'Teknologi',         'slug' => 'teknologi',          'icon' => '💻', 'description' => 'Seminar tech, hackathon, dan workshop digital.', 'sort_order' => 2],
            ['name' => 'Bisnis & Seminar',  'slug' => 'bisnis-seminar',     'icon' => '📊', 'description' => 'Workshop bisnis, networking, dan seminar profesional.', 'sort_order' => 3],
            ['name' => 'Olahraga',          'slug' => 'olahraga',           'icon' => '⚽', 'description' => 'Turnamen, fun run, dan event olahraga.', 'sort_order' => 4],
            ['name' => 'Seni & Budaya',     'slug' => 'seni-budaya',        'icon' => '🎨', 'description' => 'Pameran seni, pertunjukan budaya, dan festival.', 'sort_order' => 5],
            ['name' => 'Pendidikan',        'slug' => 'pendidikan',         'icon' => '📚', 'description' => 'Pelatihan, bootcamp, dan kelas pembelajaran.', 'sort_order' => 6],
            ['name' => 'Kuliner',           'slug' => 'kuliner',            'icon' => '🍜', 'description' => 'Festival makanan, cooking class, dan food tour.', 'sort_order' => 7],
            ['name' => 'Hiburan',           'slug' => 'hiburan',            'icon' => '🎭', 'description' => 'Stand-up comedy, teater, dan event hiburan keluarga.', 'sort_order' => 8],
        ];

        return collect($categories)->map(fn ($data) => Category::firstOrCreate(['slug' => $data['slug']], $data));
    }

    private function seedOrganizations(User $admin): \Illuminate\Support\Collection
    {
        $orgs = [
            [
                'name'        => 'Amikom Events',
                'slug'        => 'amikom-events',
                'description' => 'Event organizer resmi Universitas AMIKOM Yogyakarta. Menyelenggarakan berbagai event kampus, seminar teknologi, dan festival budaya.',
                'email'       => 'events@amikom.ac.id',
                'phone'       => '02746631234',
                'website'     => 'https://amikom.ac.id',
                'address'     => 'Jl. Ring Road Utara, Ngringin, Condongcatur, Sleman, Yogyakarta 55283',
                'status'      => 'approved',
                'approved_at' => now(),
            ],
            [
                'name'        => 'Youthopia Indonesia',
                'slug'        => 'youthopia-indonesia',
                'description' => 'Platform event pemuda terbesar di Indonesia. Fokus pada pemberdayaan generasi muda melalui event inspiratif, networking, dan creative industry.',
                'email'       => 'hello@youthopia.id',
                'phone'       => '02178901234',
                'website'     => 'https://youthopia.id',
                'address'     => 'Jl. Sudirman No. 52, Jakarta Selatan 12190',
                'status'      => 'approved',
                'approved_at' => now(),
            ],
            [
                'name'        => 'Nusantara Live',
                'slug'        => 'nusantara-live',
                'description' => 'Promotor konser dan festival musik premium. Menghadirkan artis nasional dan internasional di berbagai kota di Indonesia.',
                'email'       => 'booking@nusantaralive.com',
                'phone'       => '02129873456',
                'website'     => 'https://nusantaralive.com',
                'address'     => 'Jl. Gatot Subroto Kav. 27, Jakarta Selatan 12950',
                'status'      => 'approved',
                'approved_at' => now(),
            ],
        ];

        return collect($orgs)->map(function ($data) use ($admin) {
            return Organization::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    ...$data,
                    'owner_id'    => $admin->id,
                    'approved_by' => $admin->id,
                ]
            );
        });
    }

    private function seedEvents(
        \Illuminate\Support\Collection $categories,
        \Illuminate\Support\Collection $organizations,
        User $admin
    ): \Illuminate\Support\Collection {
        $catMap = $categories->keyBy('slug');
        [$amikomOrg, $youthopiaOrg, $nusantaraOrg] = $organizations->values()->all();

        $events = [
            // --- Amikom Events ---
            [
                'organization'   => $amikomOrg,
                'category_slug'  => 'teknologi',
                'title'          => 'AMIKOM Tech Summit 2026',
                'description'    => 'Konferensi teknologi tahunan terbesar di Yogyakarta. Menghadirkan pembicara dari perusahaan teknologi Fortune 500, startup unicorn Indonesia, dan akademisi terkemuka. Topik meliputi AI/ML, Cloud Computing, Cybersecurity, dan Future of Work.',
                'short_description' => 'Konferensi teknologi tahunan terbesar di Yogyakarta dengan 50+ pembicara dari industri global.',
                'start_date'     => now()->addDays(30),
                'location'       => 'Auditorium Universitas AMIKOM Yogyakarta',
                'venue_name'     => 'AMIKOM Auditorium',
                'poster_path'    => null,
                'status'         => 'published',
                'published_at'   => now(),
                'is_featured'    => true,
                'ticket_types'   => [
                    ['name' => 'Early Bird',    'price' => 99000,  'quantity' => 100, 'sort_order' => 1],
                    ['name' => 'Regular',       'price' => 149000, 'quantity' => 300, 'sort_order' => 2],
                    ['name' => 'VIP (Makan Siang + Sertifikat)', 'price' => 299000, 'quantity' => 50, 'sort_order' => 3],
                ],
            ],
            [
                'organization'   => $amikomOrg,
                'category_slug'  => 'pendidikan',
                'title'          => 'Workshop UI/UX Design Fundamental',
                'description'    => 'Workshop intensif 2 hari untuk mempelajari dasar-dasar UI/UX Design menggunakan Figma. Dibimbing langsung oleh desainer senior dari perusahaan produk terkemuka.',
                'short_description' => 'Workshop intensif 2 hari belajar UI/UX Design dengan Figma bersama praktisi industri.',
                'start_date'     => now()->addDays(14),
                'end_date'       => now()->addDays(15),
                'location'       => 'Lab Komputer AMIKOM Yogyakarta',
                'venue_name'     => 'Lab Kreatif AMIKOM',
                'poster_path'    => null,
                'status'         => 'published',
                'published_at'   => now(),
                'is_featured'    => false,
                'ticket_types'   => [
                    ['name' => 'Peserta Mahasiswa', 'price' => 75000,  'quantity' => 30, 'sort_order' => 1],
                    ['name' => 'Peserta Umum',      'price' => 150000, 'quantity' => 20, 'sort_order' => 2],
                ],
            ],

            // --- Youthopia Events ---
            [
                'organization'   => $youthopiaOrg,
                'category_slug'  => 'bisnis-seminar',
                'title'          => 'Indonesia Young Entrepreneurs Summit 2026',
                'description'    => 'Summit tahunan yang mempertemukan 2.000+ wirausaha muda Indonesia. Dengarkan kisah sukses founder startup, ikuti workshop intensif, dan bangun jaringan bisnis yang kuat.',
                'short_description' => 'Summit wirausaha muda terbesar di Indonesia. 2000+ peserta, 30+ pembicara, networking.',
                'start_date'     => now()->addDays(45),
                'location'       => 'Jakarta Convention Center, Jakarta Pusat',
                'venue_name'     => 'JCC Hall A & B',
                'poster_path'    => null,
                'status'         => 'published',
                'published_at'   => now(),
                'is_featured'    => true,
                'ticket_types'   => [
                    ['name' => 'Regular Pass',   'price' => 250000, 'quantity' => 1500, 'sort_order' => 1],
                    ['name' => 'Premium Pass',   'price' => 499000, 'quantity' => 400,  'sort_order' => 2],
                    ['name' => 'VIP All Access', 'price' => 999000, 'quantity' => 100,  'sort_order' => 3],
                ],
            ],
            [
                'organization'   => $youthopiaOrg,
                'category_slug'  => 'seni-budaya',
                'title'          => 'Nusantara Creative Festival 2026',
                'description'    => 'Festival kreativitas dan seni budaya nusantara yang menampilkan karya seniman dari 34 provinsi. Pameran seni, pertunjukan musik tradisional, dan workshop kerajinan tangan.',
                'short_description' => 'Festival seni budaya nusantara — seniman dari 34 provinsi dalam satu panggung.',
                'start_date'     => now()->addDays(60),
                'end_date'       => now()->addDays(63),
                'location'       => 'Taman Budaya Yogyakarta',
                'venue_name'     => 'TBY Yogyakarta',
                'poster_path'    => null,
                'status'         => 'published',
                'published_at'   => now(),
                'is_featured'    => false,
                'ticket_types'   => [
                    ['name' => 'Tiket Harian',     'price' => 50000,  'quantity' => 500, 'sort_order' => 1],
                    ['name' => 'Festival Pass (4 Hari)', 'price' => 150000, 'quantity' => 200, 'sort_order' => 2],
                ],
            ],

            // --- Nusantara Live ---
            [
                'organization'   => $nusantaraOrg,
                'category_slug'  => 'musik-konser',
                'title'          => 'Soundwave Festival 2026',
                'description'    => 'Festival musik outdoor terbesar di Jawa Tengah. Dua hari penuh, tiga panggung, 40+ artis dari genre pop, indie, jazz, dan electronic. Hadir dalam pengalaman musikal yang tak terlupakan.',
                'short_description' => 'Festival musik outdoor 2 hari, 3 panggung, 40+ artis terbaik Indonesia.',
                'start_date'     => now()->addDays(75),
                'end_date'       => now()->addDays(76),
                'location'       => 'Lapangan Kridosono, Yogyakarta',
                'venue_name'     => 'Lapangan Kridosono',
                'poster_path'    => null,
                'status'         => 'published',
                'published_at'   => now(),
                'is_featured'    => true,
                'ticket_types'   => [
                    ['name' => 'GA Day 1',          'price' => 175000, 'quantity' => 3000, 'sort_order' => 1],
                    ['name' => 'GA Day 2',          'price' => 175000, 'quantity' => 3000, 'sort_order' => 2],
                    ['name' => 'GA 2-Day Pass',     'price' => 299000, 'quantity' => 2000, 'sort_order' => 3],
                    ['name' => 'VIP 2-Day Pass',    'price' => 599000, 'quantity' => 200,  'sort_order' => 4],
                ],
            ],
            [
                'organization'   => $nusantaraOrg,
                'category_slug'  => 'hiburan',
                'title'          => 'Stand Up Comedy Spesial: Malam Komedi Indonesia',
                'description'    => 'Malam stand-up comedy eksklusif menampilkan 8 komika terbaik Indonesia. Tertawa habis-habisan bersama jokes segar, observasi tajam, dan cerita kocak yang relate.',
                'short_description' => '8 komika top Indonesia dalam satu malam penuh tawa.',
                'start_date'     => now()->addDays(20),
                'location'       => 'Ciputra Artpreneur Theater, Jakarta',
                'venue_name'     => 'Ciputra Artpreneur Theater',
                'poster_path'    => null,
                'status'         => 'published',
                'published_at'   => now(),
                'is_featured'    => false,
                'ticket_types'   => [
                    ['name' => 'Tribune',  'price' => 150000, 'quantity' => 300, 'sort_order' => 1],
                    ['name' => 'Festival', 'price' => 250000, 'quantity' => 200, 'sort_order' => 2],
                    ['name' => 'VIP',      'price' => 450000, 'quantity' => 100, 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $ticketTypesData = $eventData['ticket_types'];
            unset($eventData['ticket_types']);

            $org          = $eventData['organization'];
            $categorySlug = $eventData['category_slug'];
            unset($eventData['organization'], $eventData['category_slug']);

            $category = $catMap[$categorySlug];
            $slug     = Str::slug($eventData['title']);

            $event = Event::firstOrCreate(
                ['slug' => $slug],
                [
                    ...$eventData,
                    'organization_id' => $org->id,
                    'category_id'     => $category->id,
                    'approved_by'     => $admin->id,
                    'approved_at'     => now(),
                    'slug'            => $slug,
                ]
            );

            // Only create ticket types if the event was just created
            if ($event->wasRecentlyCreated) {
                foreach ($ticketTypesData as $ttData) {
                    $event->ticketTypes()->create($ttData);
                }
            }
            $createdEvents[] = $event;
        }
        
        return collect($createdEvents);
    }
    
    private function seedOrdersAndReviews(\Illuminate\Support\Collection $events): void
    {
        // Use firstOrCreate so re-running seeder won't create duplicate customer
        $customerUser = User::firstOrCreate(
            ['email' => 'customer@amikomhub.id'],
            [
                'name'              => 'John Customer',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $customerRole = Role::where('slug', 'customer')->first();
        if ($customerRole) {
            $customerUser->roles()->syncWithoutDetaching([$customerRole->id]);
        }
        
        foreach ($events as $event) {
            $ticketType = $event->ticketTypes->first();
            if (!$ticketType) continue;
            
            // Skip if order for this event already exists
            $orderExists = \App\Models\Order::where('event_id', $event->id)
                ->where('user_id', $customerUser->id)
                ->exists();
            if ($orderExists) continue;
            
            // Create a pending order
            $order = \App\Models\Order::create([
                'organization_id' => $event->organization_id,
                'user_id'         => $customerUser->id,
                'event_id'        => $event->id,
                'order_number'    => 'ORD-' . strtoupper(Str::random(8)),
                'customer_name'   => $customerUser->name,
                'customer_email'  => $customerUser->email,
                'customer_phone'  => '08123456789',
                'subtotal'        => $ticketType->price * 2,
                'platform_fee'    => 5000,
                'total_amount'    => ($ticketType->price * 2) + 5000,
                'status'          => 'pending',
                'expired_at'      => now()->addHours(24),
            ]);
            
            $order->items()->create([
                'ticket_type_id' => $ticketType->id,
                'quantity'       => 2,
                'unit_price'     => $ticketType->price,
                'subtotal'       => $ticketType->price * 2,
            ]);
            
            // Create a review only if it doesn't exist yet
            \App\Models\Review::firstOrCreate(
                ['event_id' => $event->id, 'user_id' => $customerUser->id],
                [
                    'organization_id' => $event->organization_id,
                    'rating'          => rand(4, 5),
                    'body'            => 'Event yang luar biasa dan sangat bermanfaat! Sampai jumpa di event berikutnya.',
                    'is_approved'     => true,
                ]
            );
        }
    }
}