<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Posts Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .page-title {
            font-size: 34px;
            font-weight: 700;
            color: #1f2937;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 15px;
        }

        .dashboard-card {
            border: none;
            border-radius: 15px;
            color: #fff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card .card-body {
            padding: 25px;
        }

        .dashboard-card i {
            font-size: 40px;
            opacity: .3;
            float: right;
        }

        .dashboard-card h2 {
            font-size: 38px;
            font-weight: bold;
        }

        .search-box {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, .08);
            margin-bottom: 25px;
        }

        .table-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, .08);
            padding: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: #0d6efd;
            color: #fff;
        }

        .table thead th {
            padding: 15px;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 15px;
        }

        .table tbody tr:hover {
            background: #f8fbff;
        }

        .content-column {
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .badge {
            padding: 8px 15px;
            font-size: 13px;
            border-radius: 20px;
        }

        .btn {
            border-radius: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            height: 48px;
        }

        .alert {
            border-radius: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        .action-buttons form {
            margin: 0;
        }

        .btn-view {
            background: #0dcaf0;
            color: #fff;
            border: none;
            min-width: 85px;
        }

        .btn-view:hover {
            background: #0aa8c9;
            color: #fff;
        }

        .btn-delete {
            background: #dc3545;
            color: #fff;
            border: none;
            min-width: 85px;
        }

        .btn-delete:hover {
            background: #bb2d3b;
            color: #fff;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 4px;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="mb-4">

            <h2 class="page-title">

                <i class="bi bi-file-earmark-text text-primary"></i>

                Posts Dashboard

            </h2>

            <div class="page-subtitle">

                Manage all factory generated posts

            </div>

        </div>

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        <!-- Dashboard Cards -->

        <div class="row g-4 mb-4">

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-primary">

                    <div class="card-body">

                        <i class="bi bi-files"></i>

                        <h6>Total Posts</h6>

                        <h2>{{ $totalPosts }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-success">

                    <div class="card-body">

                        <i class="bi bi-check-circle"></i>

                        <h6>Published</h6>

                        <h2>{{ $publishedPosts }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-warning text-dark">

                    <div class="card-body">

                        <i class="bi bi-x-circle"></i>

                        <h6>Unpublished</h6>

                        <h2>{{ $unpublishedPosts }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-info">

                    <div class="card-body">

                        <i class="bi bi-calendar-event"></i>

                        <h6>Today's Posts</h6>

                        <h2>{{ $todayPosts }}</h2>

                    </div>

                </div>

            </div>

        </div>

        <!-- Advanced Analytics -->

        <div class="row g-4 mb-4">

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-dark">

                    <div class="card-body">

                        <i class="bi bi-eye-fill"></i>

                        <h6>Total Views</h6>

                        <h2>{{ number_format($totalViews) }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-secondary">

                    <div class="card-body">

                        <i class="bi bi-bar-chart-fill"></i>

                        <h6>Average Views</h6>

                        <h2>{{ number_format($averageViews) }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-danger">

                    <div class="card-body">

                        <i class="bi bi-fire"></i>

                        <h6>Highest Views</h6>

                        <h2>{{ number_format($highestViews) }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card dashboard-card bg-success">

                    <div class="card-body">

                        <i class="bi bi-star-fill"></i>

                        <h6>Posts (1000+ Views)</h6>

                        <h2>{{ $popularPosts }}</h2>

                    </div>

                </div>

            </div>

        </div>

        <!-- Search Box -->

        <div class="search-box">

            <form method="GET" action="{{ route('posts.index') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search title, user or content">

                    </div>

                    <div class="col-md-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">All Posts</option>

                            <option value="1" {{ request('status')=='1' ? 'selected' : '' }}>

                                Published

                            </option>

                            <option value="0" {{ request('status')=='0' ? 'selected' : '' }}>

                                Unpublished

                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                        <a href="{{ route('posts.index') }}"
                            class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                            Clear
                        </a>

                        <a href="{{ route('posts.export.csv') }}"
                            class="btn btn-success">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                            CSV
                        </a>

                        <a href="{{ route('posts.export.json') }}"
                            class="btn btn-dark">
                            <i class="bi bi-filetype-json"></i>
                            JSON
                        </a>

                    </div>

                </div>

            </form>

        </div>

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <strong>Published Posts Percentage</strong>

                    <strong>{{ $publishedPercentage }}%</strong>

                </div>

                <div class="progress mt-3" style="height:22px;">

                    <div class="progress-bar bg-success"

                        style="width: {{ $publishedPercentage }}%;">

                        {{ $publishedPercentage }}%

                    </div>

                </div>

            </div>

        </div>

        <div class="table-card">

            <table class="table table-hover table-bordered align-middle">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Title</th>

                        <th>Content</th>

                        <th>Author</th>

                        <th>Views</th>

                        <th>Status</th>

                        <th width="170">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($posts as $post)

                    <tr>

                        <td>{{ $post->id }}</td>

                        <td>

                            <strong>{{ $post->title }}</strong>

                        </td>

                        <td class="content-column">

                            {{ \Illuminate\Support\Str::limit($post->content, 80) }}

                        </td>

                        <td>

                            <i class="bi bi-person-circle text-primary"></i>

                            {{ $post->user?->name ?? 'N/A' }}

                        </td>

                        <td>

                            <span class="badge bg-secondary">

                                <i class="bi bi-eye-fill"></i>

                                {{ number_format($post->views) }}

                            </span>

                        </td>

                        <td>

                            @if($post->is_published)

                            <span class="badge bg-success">

                                <i class="bi bi-check-circle-fill"></i>

                                Published

                            </span>

                            @else

                            <span class="badge bg-danger">

                                <i class="bi bi-x-circle-fill"></i>

                                Unpublished

                            </span>

                            @endif

                        </td>

                        <td>

                            <div class="action-buttons">

                                <a href="{{ route('posts.show',$post) }}"
                                    class="btn btn-view btn-sm">

                                    <i class="bi bi-eye-fill"></i>

                                    View

                                </a>

                                <form action="{{ route('posts.destroy',$post) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this post?')">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                        class="btn btn-delete btn-sm">

                                        <i class="bi bi-trash-fill"></i>

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="text-center py-5">

                            <i class="bi bi-file-earmark-x display-4 text-secondary"></i>

                            <h5 class="mt-3 text-muted">

                                No Posts Found

                            </h5>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

            <!-- Number Pagination -->

            <div class="d-flex justify-content-center mt-4">

                @if ($posts->hasPages())

                <nav>

                    <ul class="pagination">

                        @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)

                        <li class="page-item {{ $page == $posts->currentPage() ? 'active' : '' }}">

                            <a class="page-link" href="{{ $url }}">

                                {{ $page }}

                            </a>

                        </li>

                        @endforeach

                    </ul>

                </nav>

                @endif

            </div>

            <hr class="my-5">

            <h4 class="mb-3">

                <i class="bi bi-trophy-fill text-warning"></i>

                Top 5 Most Viewed Posts

            </h4>

            <div class="table-responsive mb-5">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Title</th>

                            <th>Author</th>

                            <th>Views</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($topViewedPosts as $post)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $post->title }}</td>

                            <td>{{ $post->user?->name ?? 'N/A' }}</td>

                            <td>

                                <span class="badge bg-danger">

                                    {{ number_format($post->views) }}

                                </span>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <h4 class="mb-3">

                <i class="bi bi-clock-history text-primary"></i>

                Latest Generated Posts

            </h4>

            <div class="table-responsive mb-4">

                <table class="table table-bordered table-striped">

                    <thead class="table-primary">

                        <tr>

                            <th>ID</th>

                            <th>Title</th>

                            <th>Author</th>

                            <th>Created</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($recentPosts as $post)

                        <tr>

                            <td>{{ $post->id }}</td>

                            <td>{{ $post->title }}</td>

                            <td>{{ $post->user?->name ?? 'N/A' }}</td>

                            <td>{{ $post->created_at->format('d M Y H:i') }}</td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>



        </div>

    </div>

</body>

</html>