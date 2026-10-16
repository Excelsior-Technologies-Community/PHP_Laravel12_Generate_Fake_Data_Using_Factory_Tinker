<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\FactoryStudioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    protected FactoryStudioService $factoryService;

    public function __construct(FactoryStudioService $factoryService)
    {
        $this->factoryService = $factoryService;
    }

    public function index(Request $request)
    {
        $query = Post::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status Filter
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_published', (bool) $request->status);
        }

        // Category Filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('id'),
            'views_desc' => $query->orderByDesc('views'),
            'views_asc' => $query->orderBy('views'),
            'title_asc' => $query->orderBy('title'),
            default => $query->latest('id'),
        };

        $posts = $query->paginate(8)->withQueryString();

        // Statistics
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', 1)->count();
        $unpublishedPosts = Post::where('is_published', 0)->count();
        $todayPosts = Post::whereDate('created_at', today())->count();

        // Analytics
        $totalViews = Post::sum('views');
        $averageViews = round(Post::avg('views') ?? 0);
        $highestViews = Post::max('views') ?? 0;
        $popularPosts = Post::where('views', '>=', 5000)->count();

        $publishedPercentage = $totalPosts > 0
            ? round(($publishedPosts / $totalPosts) * 100, 1)
            : 0;

        // Top 5 Most Viewed Posts
        $topViewedPosts = Post::with('user')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // Latest 5 Posts
        $recentPosts = Post::with('user')
            ->latest('id')
            ->take(5)
            ->get();

        // Comprehensive Visual Analytics for Chart.js
        $analytics = $this->factoryService->getAnalyticsData();

        return view('posts.index', compact(
            'posts',
            'totalPosts',
            'publishedPosts',
            'unpublishedPosts',
            'todayPosts',
            'totalViews',
            'averageViews',
            'highestViews',
            'popularPosts',
            'publishedPercentage',
            'topViewedPosts',
            'recentPosts',
            'analytics'
        ));
    }

    public function show(Post $post)
    {
        $post->load('user');
        return view('posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function exportCsv()
    {
        $fileName = 'posts_' . now()->format('Y-m-d_His') . '.csv';
        $posts = Post::with('user')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($posts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID',
                'Title',
                'Category',
                'Content',
                'Author',
                'Views',
                'Status',
                'Created At'
            ]);

            foreach ($posts as $post) {
                fputcsv($file, [
                    $post->id,
                    $post->title,
                    $post->category ?? 'General',
                    strip_tags($post->content),
                    $post->user?->name ?? 'N/A',
                    $post->views,
                    $post->is_published ? 'Published' : 'Unpublished',
                    $post->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportJson()
    {
        $posts = Post::with('user')->get()->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'category' => $post->category ?? 'General',
                'content' => $post->content,
                'author' => $post->user?->name ?? 'N/A',
                'views' => $post->views,
                'status' => $post->is_published ? 'Published' : 'Unpublished',
                'created_at' => $post->created_at,
            ];
        });

        return response()->json($posts)
            ->header(
                'Content-Disposition',
                'attachment; filename=posts_' . now()->format('Y-m-d_His') . '.json'
            );
    }
}
