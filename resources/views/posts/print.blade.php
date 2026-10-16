<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Catalog & Analytics Report - Laravel 12 Fake Data Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b; background: #fff; }
        .report-header { border-bottom: 2px solid #0284c7; padding-bottom: 1rem; margin-bottom: 2rem; }
        .stat-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background: #f8fafc; }
        .badge-cat { font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 600; }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            .table { font-size: 11px; }
        }
    </style>
</head>
<body class="p-4">
    <div class="container-fluid">
        <!-- Print Controls -->
        <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded border">
            <div>
                <strong>Printable Report Preview</strong>
                <span class="text-muted ms-2">Click "Print / Save PDF" to generate hard copy or PDF output.</span>
            </div>
            <div>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm me-2">Back to Dashboard</a>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    Print / Save as PDF
                </button>
            </div>
        </div>

        <!-- Header -->
        <div class="report-header d-flex justify-content-between align-items-end">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Posts Catalog &amp; Analytics Report</h2>
                <p class="text-muted mb-0">Generated from Laravel 12 Fake Data Studio &bull; Date: {{ now()->format('F j, Y - H:i:s') }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary fs-6">Total Posts: {{ $posts->count() }}</span>
            </div>
        </div>

        <!-- Executive Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-3">
                <div class="stat-box text-center">
                    <div class="text-muted small">Total Posts</div>
                    <div class="fs-4 fw-bold text-dark">{{ $posts->count() }}</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-box text-center">
                    <div class="text-muted small">Published Posts</div>
                    <div class="fs-4 fw-bold text-success">{{ $posts->where('is_published', true)->count() }}</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-box text-center">
                    <div class="text-muted small">Total Views</div>
                    <div class="fs-4 fw-bold text-info">{{ number_format($posts->sum('views')) }}</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-box text-center">
                    <div class="text-muted small">Average Views / Post</div>
                    <div class="fs-4 fw-bold text-warning">{{ $posts->count() > 0 ? number_format(round($posts->avg('views'))) : 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Category Breakdown Table -->
        <h5 class="fw-bold mb-2">Category Distribution</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        @foreach($analytics['category_chart']['labels'] as $cat)
                            <th>{{ $cat }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($analytics['category_chart']['data'] as $count)
                            <td class="fw-semibold">{{ $count }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Detailed Posts Table -->
        <h5 class="fw-bold mb-2">Detailed Post Inventory</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td class="fw-semibold">{{ $post->title }}</td>
                            <td><span class="badge bg-secondary">{{ $post->category ?? 'General' }}</span></td>
                            <td>{{ $post->user?->name ?? 'N/A' }}</td>
                            <td>
                                @if($post->is_published)
                                    <span class="text-success fw-bold">Published</span>
                                @else
                                    <span class="text-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ number_format($post->views) }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No posts available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center text-muted small mt-4 pt-4 border-top">
            End of Report &bull; Laravel 12 Fake Data Studio &bull; All data generated for sandbox testing.
        </div>
    </div>
</body>
</html>
