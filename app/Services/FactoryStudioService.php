<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FactoryStudioService
{
    /**
     * Generate fake posts with custom count, states, and date range
     */
    public function generate(array $params): array
    {
        $count = (int) ($params['count'] ?? 10);
        $count = max(1, min($count, 1000)); // enforce 1 to 1000 bounds

        $state = $params['state'] ?? 'random';
        $category = $params['category'] ?? 'all';
        $dateRange = $params['date_range'] ?? 'past_year';
        $authorStrategy = $params['author_strategy'] ?? 'existing_or_create';

        $startTime = microtime(true);

        // Fetch or create authors pool
        $authors = User::pluck('id')->toArray();
        if (empty($authors) || $authorStrategy === 'create_fresh') {
            $createdUsers = User::factory()->count(min(15, max(3, (int)($count / 10))))->create();
            $authors = $createdUsers->pluck('id')->toArray();
        }

        $categories = ['Technology', 'Science', 'Design', 'Business', 'Lifestyle', 'Education'];

        // Determine date boundary
        $now = now();
        $fromDate = match ($dateRange) {
            'today' => $now->copy()->startOfDay(),
            'past_week' => $now->copy()->subWeek(),
            'past_month' => $now->copy()->subMonth(),
            'past_quarter' => $now->copy()->subMonths(3),
            default => $now->copy()->subYear(),
        };

        // Batch generation in chunks of 100 for high performance
        $chunkSize = 100;
        $totalCreated = 0;
        $faker = \Faker\Factory::create();

        while ($totalCreated < $count) {
            $currentChunk = min($chunkSize, $count - $totalCreated);
            $records = [];

            for ($i = 0; $i < $currentChunk; $i++) {
                $authorId = $authors[array_rand($authors)];
                $chosenCat = ($category !== 'all' && in_array($category, $categories))
                    ? $category
                    : $categories[array_rand($categories)];

                // Determine is_published & views by state
                $isPublished = match ($state) {
                    'published' => true,
                    'unpublished' => false,
                    'popular' => true,
                    default => $faker->boolean(75),
                };

                $views = match ($state) {
                    'popular' => $faker->numberBetween(15000, 95000),
                    'unpublished' => 0,
                    default => $faker->numberBetween(10, 12000),
                };

                $randomTimestamp = $faker->dateTimeBetween($fromDate, $now)->format('Y-m-d H:i:s');
                $publishedAt = $isPublished ? $randomTimestamp : null;

                $records[] = [
                    'title' => rtrim($faker->sentence(rand(4, 9)), '.'),
                    'category' => $chosenCat,
                    'content' => implode("\n\n", $faker->paragraphs(rand(2, 4))),
                    'author_id' => $authorId,
                    'is_published' => $isPublished,
                    'views' => $views,
                    'published_at' => $publishedAt,
                    'created_at' => $randomTimestamp,
                    'updated_at' => $randomTimestamp,
                ];
            }

            Post::insert($records);
            $totalCreated += $currentChunk;
        }

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'success' => true,
            'count' => $totalCreated,
            'duration_ms' => $durationMs,
            'message' => "Successfully generated {$totalCreated} fake posts in {$durationMs} ms.",
        ];
    }

    /**
     * Purge all fake posts, preserving admin/baseline users
     */
    public function purge(): array
    {
        $postsDeleted = Post::count();
        Post::truncate();

        // Keep at least 1 user if exists, or remove users with no posts
        $usersPreserved = User::count();

        return [
            'success' => true,
            'posts_deleted' => $postsDeleted,
            'users_count' => $usersPreserved,
            'message' => "Successfully purged {$postsDeleted} posts from database.",
        ];
    }

    /**
     * Reset database to clean 10 baseline sample posts
     */
    public function resetToBaseline(): array
    {
        Post::truncate();

        // Ensure baseline admin user exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Developer',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $author2 = User::firstOrCreate(
            ['email' => 'sarah.connor@example.com'],
            [
                'name' => 'Sarah Connor',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $baselinePosts = [
            [
                'title' => 'Mastering Laravel 12 Eloquent Factories and Tinker Studio',
                'category' => 'Technology',
                'content' => 'Laravel 12 introduces powerful enhancements to model factories and testing workflows. In this deep dive, we explore state modifiers, sequence definitions, and interactive REPL environments.',
                'author_id' => $admin->id,
                'is_published' => true,
                'views' => 12500,
                'published_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'High-Performance Database Seeding for Enterprise Scale',
                'category' => 'Technology',
                'content' => 'When seeding hundreds of thousands of records, single-record model instantiation can exhaust memory. Batch chunking and direct mass insertion provide 10x throughput gains.',
                'author_id' => $admin->id,
                'is_published' => true,
                'views' => 8400,
                'published_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Draft Architecture: Next-Gen Serverless Microservices',
                'category' => 'Science',
                'content' => 'Exploration of event-driven decoupled systems. This architectural RFC is currently under internal review.',
                'author_id' => $author2->id,
                'is_published' => false,
                'views' => 0,
                'published_at' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Modern UI Design Systems with Tailwind CSS and Bootstrap',
                'category' => 'Design',
                'content' => 'Building consistent design tokens and accessible component libraries across cross-functional engineering teams.',
                'author_id' => $author2->id,
                'is_published' => true,
                'views' => 6200,
                'published_at' => now()->subDays(10),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'The Economics of SaaS Churn and Customer Lifetime Value',
                'category' => 'Business',
                'content' => 'Analyzing cohort metrics and subscription retention strategies to maximize annualized revenue.',
                'author_id' => $admin->id,
                'is_published' => true,
                'views' => 18900,
                'published_at' => now()->subDays(15),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'title' => 'Mindfulness and Cognitive Ergonomics for Remote Engineers',
                'category' => 'Lifestyle',
                'content' => 'Strategies to mitigate context switching fatigue, maintain deep work sessions, and foster sustainable productivity.',
                'author_id' => $author2->id,
                'is_published' => true,
                'views' => 3400,
                'published_at' => now()->subDays(20),
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'title' => 'Future of Artificial General Intelligence and Agentic Workflows',
                'category' => 'Science',
                'content' => 'How autonomous multi-agent systems are shifting paradigm from code generation to full-lifecycle software delivery.',
                'author_id' => $admin->id,
                'is_published' => true,
                'views' => 31200,
                'published_at' => now()->subDays(25),
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'title' => 'Unpublished Review: Zero-Trust Security Models',
                'category' => 'Technology',
                'content' => 'Upcoming security audit guide covering mutual TLS, identity verification, and ephemeral secrets.',
                'author_id' => $author2->id,
                'is_published' => false,
                'views' => 0,
                'published_at' => null,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'title' => 'Continuous Learning and Technical Pedagogy in 2026',
                'category' => 'Education',
                'content' => 'Structuring effective onboarding paths for junior engineers in modern distributed engineering teams.',
                'author_id' => $admin->id,
                'is_published' => true,
                'views' => 4800,
                'published_at' => now()->subDays(12),
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'title' => 'Designing Resilient Distributed Cache Topologies with Redis',
                'category' => 'Technology',
                'content' => 'Cache stampede prevention, cluster sharding, and real-time invalidation patterns.',
                'author_id' => $author2->id,
                'is_published' => true,
                'views' => 9700,
                'published_at' => now()->subDays(18),
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
        ];

        Post::insert($baselinePosts);

        return [
            'success' => true,
            'posts_created' => count($baselinePosts),
            'message' => 'Database successfully reset to 10 clean baseline sample posts.',
        ];
    }

    /**
     * Compute analytics metrics for Chart.js and dashboard widgets
     */
    public function getAnalyticsData(): array
    {
        $totalPosts = Post::count();
        $publishedCount = Post::where('is_published', true)->count();
        $unpublishedCount = Post::where('is_published', false)->count();

        // 1. Category Distribution
        $categoryCounts = Post::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $allCategories = ['Technology', 'Science', 'Design', 'Business', 'Lifestyle', 'Education'];
        $catLabels = [];
        $catData = [];
        foreach ($allCategories as $cat) {
            $catLabels[] = $cat;
            $catData[] = $categoryCounts[$cat] ?? 0;
        }

        // 2. Views Histogram
        $viewsLow = Post::whereBetween('views', [0, 1000])->count();
        $viewsMedium = Post::whereBetween('views', [1001, 5000])->count();
        $viewsHigh = Post::whereBetween('views', [5001, 20000])->count();
        $viewsViral = Post::where('views', '>', 20000)->count();

        // 3. Monthly Publication Timeline (Last 6 Months)
        $months = [];
        $monthCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthLabel = $monthDate->format('M Y');
            $months[] = $monthLabel;

            $countInMonth = Post::whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->count();
            $monthCounts[] = $countInMonth;
        }

        // 4. Author Leaderboard
        $authorsLeaderboard = User::has('posts')
            ->withCount('posts')
            ->withSum('posts as total_views', 'views')
            ->orderByDesc('total_views')
            ->take(5)
            ->get()
            ->map(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'email' => $author->email,
                    'posts_count' => $author->posts_count,
                    'total_views' => (int) ($author->total_views ?? 0),
                    'avg_views' => $author->posts_count > 0 ? round($author->total_views / $author->posts_count) : 0,
                ];
            });

        return [
            'status_chart' => [
                'labels' => ['Published', 'Unpublished / Draft'],
                'data' => [$publishedCount, $unpublishedCount],
            ],
            'category_chart' => [
                'labels' => $catLabels,
                'data' => $catData,
            ],
            'views_histogram' => [
                'labels' => ['0 - 1K (Low)', '1K - 5K (Medium)', '5K - 20K (High)', '20K+ (Viral)'],
                'data' => [$viewsLow, $viewsMedium, $viewsHigh, $viewsViral],
            ],
            'timeline_chart' => [
                'labels' => $months,
                'data' => $monthCounts,
            ],
            'author_leaderboard' => $authorsLeaderboard,
        ];
    }
}
