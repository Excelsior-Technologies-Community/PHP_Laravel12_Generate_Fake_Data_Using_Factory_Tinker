<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter
        if ($request->status != '') {
            $query->where('is_published', $request->status);
        }

        $posts = $query->oldest()->paginate(6)->withQueryString();

        // Dashboard Statistics
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', 1)->count();
        $unpublishedPosts = Post::where('is_published', 0)->count();
        $todayPosts = Post::whereDate('created_at', today())->count();

        return view('posts.index', compact(
            'posts',
            'totalPosts',
            'publishedPosts',
            'unpublishedPosts',
            'todayPosts'
        ));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
