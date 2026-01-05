<div class="container mt-5">
    <h1>{{ $post->title }}</h1>
    <p><strong>Author:</strong> {{ $post->user->name ?? $post->author }}</p>
    <p><strong>Views:</strong> {{ $post->views }}</p>
    <p><strong>Published:</strong> {{ $post->published_at->format('Y-m-d H:i') }}</p>
    <div class="content mt-3">
        {{ $post->content }}
    </div>
    <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>