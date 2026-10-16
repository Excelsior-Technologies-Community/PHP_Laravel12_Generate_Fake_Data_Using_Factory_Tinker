<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fake Data Studio &amp; Tinker Playground - Laravel 12</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-accent: #0284c7;
            --primary-hover: #0369a1;
            --bg-canvas: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            background-color: var(--bg-canvas);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
        }

        .stat-card {
            border: 1px solid var(--card-border);
            border-radius: 14px;
            background: #ffffff;
            padding: 1.25rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px -4px rgba(0, 0, 0, 0.1);
        }

        .studio-nav-pills .nav-link {
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .studio-nav-pills .nav-link.active {
            color: #ffffff;
            background-color: var(--primary-accent);
            box-shadow: 0 4px 10px -2px rgba(2, 132, 199, 0.4);
        }

        .studio-nav-pills .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 2px 8px -2px rgba(0, 0, 0, 0.05);
        }

        .code-editor-box {
            background-color: #0f172a;
            color: #38bdf8;
            font-family: 'Consolas', 'Fira Code', Courier, monospace;
            font-size: 14px;
            border-radius: 10px;
            border: 1px solid #1e293b;
            padding: 14px;
            resize: vertical;
            width: 100%;
            min-height: 140px;
        }

        .code-editor-box:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);
        }

        .snippet-btn {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #334155;
            transition: all 0.15s;
        }

        .snippet-btn:hover {
            background-color: #f1f5f9;
            border-color: #94a3b8;
            color: #0f172a;
        }

        .badge-category {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .category-Technology { background-color: #e0f2fe; color: #0369a1; }
        .category-Science { background-color: #ede9fe; color: #6d28d9; }
        .category-Design { background-color: #fce7f3; color: #be185d; }
        .category-Business { background-color: #fef3c7; color: #b45309; }
        .category-Lifestyle { background-color: #dcfce7; color: #15803d; }
        .category-Education { background-color: #ffedd5; color: #c2410c; }
        .category-General { background-color: #f1f5f9; color: #475569; }

        .slider-counter-bubble {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-accent);
        }
    </style>
</head>

<body class="py-4">

    <div class="container-xl">

        <!-- Top Header & Global Actions -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-2.5 py-1.5 rounded-pill"><i class="bi bi-cpu-fill me-1"></i> Laravel 12</span>
                    <h2 class="fw-bold mb-0 text-slate-900">Fake Data Studio &amp; Tinker Playground</h2>
                </div>
                <p class="text-muted small mb-0 mt-1">Interactive Model Factory Generation &bull; Live Web Tinker REPL &bull; Database Sandbox Tools &bull; Visual Analytics</p>
            </div>

            <!-- Global Action Buttons -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Export Suite -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i> Export Data
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('posts.export.csv') }}"><i class="bi bi-filetype-csv text-success me-2"></i> Export CSV</a></li>
                        <li><a class="dropdown-item" href="{{ route('posts.export.json') }}"><i class="bi bi-filetype-json text-warning me-2"></i> Export JSON</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('posts.export.print') }}" target="_blank"><i class="bi bi-printer text-primary me-2"></i> Printable PDF Report</a></li>
                    </ul>
                </div>

                <!-- Reset / Purge Sandbox Controls -->
                <button type="button" class="btn btn-outline-warning btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#resetModal">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Baseline
                </button>

                <button type="button" class="btn btn-outline-danger btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#purgeModal">
                    <i class="bi bi-trash3 me-1"></i> Purge All
                </button>
            </div>
        </div>

        <!-- Global Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Summary Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>TOTAL POSTS</span>
                        <i class="bi bi-database text-primary fs-5"></i>
                    </div>
                    <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalPosts) }}</div>
                    <div class="small text-muted mt-1">Today: <strong class="text-primary">+{{ $todayPosts }}</strong></div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>PUBLISHED RATIO</span>
                        <i class="bi bi-check2-circle text-success fs-5"></i>
                    </div>
                    <div class="fs-3 fw-bold text-success mt-1">{{ $publishedPercentage }}%</div>
                    <div class="small text-muted mt-1">Pub: <strong>{{ $publishedPosts }}</strong> | Draft: <strong>{{ $unpublishedPosts }}</strong></div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>TOTAL VIEWS</span>
                        <i class="bi bi-eye text-info fs-5"></i>
                    </div>
                    <div class="fs-3 fw-bold text-info mt-1">{{ number_format($totalViews) }}</div>
                    <div class="small text-muted mt-1">Avg/Post: <strong>{{ number_format($averageViews) }}</strong></div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>VIRAL CONTENT (5K+)</span>
                        <i class="bi bi-fire text-danger fs-5"></i>
                    </div>
                    <div class="fs-3 fw-bold text-danger mt-1">{{ number_format($popularPosts) }}</div>
                    <div class="small text-muted mt-1">Max: <strong>{{ number_format($highestViews) }}</strong> views</div>
                </div>
            </div>
        </div>

        <!-- Main Workspace Tabs -->
        <ul class="nav nav-pills studio-nav-pills gap-2 mb-4" id="studioTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="generator-tab" data-bs-toggle="pill" data-bs-target="#generator-pane" type="button" role="tab">
                    <i class="bi bi-sliders2 me-1"></i> 1-Click Data Generator
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tinker-tab" data-bs-toggle="pill" data-bs-target="#tinker-pane" type="button" role="tab">
                    <i class="bi bi-terminal-fill me-1"></i> Web Tinker Console
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics-pane" type="button" role="tab">
                    <i class="bi bi-pie-chart-fill me-1"></i> Visual Analytics &amp; Insights
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="catalog-tab" data-bs-toggle="pill" data-bs-target="#catalog-pane" type="button" role="tab">
                    <i class="bi bi-table me-1"></i> Posts Catalog &amp; Filter
                </button>
            </li>
        </ul>

        <div class="tab-content" id="studioTabContent">

            <!-- TAB 1: 1-Click Interactive Factory Studio -->
            <div class="tab-pane fade show active" id="generator-pane" role="tabpanel">
                <div class="content-card p-4">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h4 class="fw-bold text-dark mb-1"><i class="bi bi-magic text-primary me-2"></i> Interactive Factory Generator Studio</h4>
                            <p class="text-muted small mb-0">Generate realistic mock posts and users with custom states, categories, and date ranges without running CLI commands.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-light text-dark border px-3 py-2">
                                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Chunked Batch Generation (Zero Memory Leaks)
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('posts.generate') }}" method="POST" id="factoryGeneratorForm">
                        @csrf
                        <div class="bg-light p-4 rounded-3 border mb-4">
                            <!-- Slider & Custom Count -->
                            <div class="row align-items-center mb-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold d-flex justify-content-between align-items-center mb-1">
                                        <span>Select Quantity to Generate:</span>
                                        <span class="slider-counter-bubble" id="sliderValueDisplay">50</span>
                                    </label>
                                    <input type="range" class="form-range" name="count" id="countRangeSlider" min="1" max="1000" value="50" step="1" oninput="updateSliderValue(this.value)">
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                        <span>1 record</span>
                                        <span>250</span>
                                        <span>500</span>
                                        <span>750</span>
                                        <span>1,000 records</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <span class="text-muted small d-block mb-1">Quick Presets:</span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" onclick="setSliderValue(10)">+10</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setSliderValue(50)">+50</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setSliderValue(100)">+100</button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="setSliderValue(500)">+500</button>
                                        <button type="button" class="btn btn-outline-primary fw-bold" onclick="setSliderValue(1000)">1K</button>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Configuration Options -->
                            <div class="row g-3">
                                <!-- Factory State Selector -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Factory State Modifiers</label>
                                    <select class="form-select" name="state">
                                        <option value="random" selected>Balanced Mixed (75% Published, 25% Drafts)</option>
                                        <option value="published">All Published (100% Published)</option>
                                        <option value="unpublished">All Drafts (Unpublished Backlog)</option>
                                        <option value="popular">Viral Popular (15,000 - 95,000 Views)</option>
                                    </select>
                                </div>

                                <!-- Category Selection -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Category Scope</label>
                                    <select class="form-select" name="category">
                                        <option value="all" selected>All Categories (Randomized Distribution)</option>
                                        <option value="Technology">Technology</option>
                                        <option value="Science">Science</option>
                                        <option value="Design">Design</option>
                                        <option value="Business">Business</option>
                                        <option value="Lifestyle">Lifestyle</option>
                                        <option value="Education">Education</option>
                                    </select>
                                </div>

                                <!-- Date Range Picker -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Publication Timeline Range</label>
                                    <select class="form-select" name="date_range">
                                        <option value="past_year" selected>Past 1 Year (Historical Spread)</option>
                                        <option value="past_quarter">Past 3 Months (Quarterly Activity)</option>
                                        <option value="past_month">Past 30 Days (Recent Month)</option>
                                        <option value="past_week">Past 7 Days (Weekly Surge)</option>
                                        <option value="today">Today Only (Instant Activity)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 fw-bold shadow-sm" id="btnGenerateSubmit">
                                <i class="bi bi-plus-circle-fill me-1"></i> Generate Fake Records
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2: Web Tinker Console & Code Sandbox -->
            <div class="tab-pane fade" id="tinker-pane" role="tabpanel">
                <div class="content-card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                        <div>
                            <h4 class="fw-bold text-dark mb-1"><i class="bi bi-terminal-fill text-info me-2"></i> Web Tinker Console &amp; Code Sandbox</h4>
                            <p class="text-muted small mb-0">Execute real-time Eloquent queries and Factory commands safely directly in browser.</p>
                        </div>
                        <span class="badge bg-dark text-info px-3 py-2 font-monospace">
                            <i class="bi bi-shield-check text-success me-1"></i> Sandbox Guard Active
                        </span>
                    </div>

                    <!-- Quick Snippet Shortcuts -->
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold me-2">Quick Snippets:</span>
                        <div class="d-inline-flex flex-wrap gap-1 align-items-center">
                            <button type="button" class="snippet-btn" onclick="injectSnippet('Post::count();')">Count Posts</button>
                            <button type="button" class="snippet-btn" onclick="injectSnippet('Post::with(\'user\')->popular(10000)->take(5)->get();')">Top 5 Popular</button>
                            <button type="button" class="snippet-btn" onclick="injectSnippet('Post::selectRaw(\'category, count(*) as count, round(avg(views)) as avg_views\')->groupBy(\'category\')->get();')">Category Averages</button>
                            <button type="button" class="snippet-btn" onclick="injectSnippet('User::withCount(\'posts\')->having(\'posts_count\', \'>\', 0)->take(5)->get();')">Active Authors</button>
                            <button type="button" class="snippet-btn" onclick="injectSnippet('Post::factory()->count(10)->published()->create();')">Generate 10 Published</button>
                            <button type="button" class="snippet-btn" onclick="injectSnippet('Post::where(\'is_published\', false)->count();')">Drafts Count</button>
                        </div>
                    </div>

                    <!-- Code Editor Box -->
                    <div class="mb-3">
                        <textarea id="tinkerCodeInput" class="code-editor-box" rows="4" placeholder="// Enter Eloquent or Factory expression here&#10;Post::with('user')->latest()->take(3)->get();">Post::with('user')->latest()->take(3)->get();</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted small">Press <strong>Run Snippet</strong> to execute in live Laravel environment.</span>
                        <button type="button" class="btn btn-dark fw-bold px-4 py-2" id="btnRunTinker" onclick="executeTinkerCode()">
                            <i class="bi bi-play-fill me-1 text-warning"></i> Run Snippet
                        </button>
                    </div>

                    <!-- Execution Results Area -->
                    <div id="tinkerResultsWrapper" class="border rounded-3 p-3 bg-light" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <span class="badge bg-success" id="tinkerStatusBadge">Executed Successfully</span>
                                <span class="badge bg-secondary ms-1 font-monospace" id="tinkerExecutionTime">0 ms</span>
                                <span class="badge bg-info text-dark ms-1" id="tinkerQueriesCount">0 Queries</span>
                            </div>
                            <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none p-0" onclick="copyTinkerOutput()">
                                <i class="bi bi-clipboard"></i> Copy Output
                            </button>
                        </div>

                        <!-- Result Pre Area -->
                        <div class="bg-dark rounded p-3 text-light overflow-auto font-monospace" style="max-height: 380px;">
                            <pre class="mb-0 text-info" id="tinkerOutputText"></pre>
                        </div>

                        <!-- SQL Queries Accordion -->
                        <div id="queriesListContainer" class="mt-2 text-muted small" style="display: none;">
                            <details>
                                <summary class="fw-semibold text-primary cursor-pointer">View Executed SQL Queries (<span id="sqlCountLabel">0</span>)</summary>
                                <ul class="list-group list-group-flush font-monospace mt-1" id="sqlQueriesList" style="font-size: 12px;"></ul>
                            </details>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Visual Analytics & Insights (Chart.js) -->
            <div class="tab-pane fade" id="analytics-pane" role="tabpanel">
                <div class="row g-4">
                    <!-- Status Distribution Donut -->
                    <div class="col-md-4">
                        <div class="content-card p-3 h-100">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-pie-chart text-success me-1"></i> Publication Status</h6>
                            <p class="text-muted small mb-2">Published vs Draft ratio</p>
                            <div style="height: 220px;">
                                <canvas id="chartStatus"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Category Distribution Donut -->
                    <div class="col-md-4">
                        <div class="content-card p-3 h-100">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-tags text-primary me-1"></i> Category Breakdown</h6>
                            <p class="text-muted small mb-2">Distribution across 6 categories</p>
                            <div style="height: 220px;">
                                <canvas id="chartCategory"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Views Histogram -->
                    <div class="col-md-4">
                        <div class="content-card p-3 h-100">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-bar-chart-fill text-warning me-1"></i> Views Distribution</h6>
                            <p class="text-muted small mb-2">Histogram across traffic tiers</p>
                            <div style="height: 220px;">
                                <canvas id="chartHistogram"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Activity Line Chart -->
                    <div class="col-md-7">
                        <div class="content-card p-4">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-graph-up text-primary me-1"></i> 6-Month Publication Velocity</h6>
                            <p class="text-muted small mb-3">Volume of posts generated per calendar month</p>
                            <div style="height: 240px;">
                                <canvas id="chartTimeline"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Author Leaderboard -->
                    <div class="col-md-5">
                        <div class="content-card p-4">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-trophy text-warning me-1"></i> Top Authors Leaderboard</h6>
                            <p class="text-muted small mb-3">Ranked by cumulative post volume &amp; views</p>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0 text-start">
                                    <thead class="table-light small">
                                        <tr>
                                            <th>Author</th>
                                            <th>Posts</th>
                                            <th>Total Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($analytics['author_leaderboard'] as $author)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $author['name'] }}</div>
                                                    <div class="text-muted" style="font-size: 11px;">{{ $author['email'] }}</div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $author['posts_count'] }}</span></td>
                                                <td class="fw-bold text-primary">{{ number_format($author['total_views']) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">No author data available.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: Posts Catalog, Search & Filter -->
            <div class="tab-pane fade" id="catalog-pane" role="tabpanel">
                <div class="content-card p-4">
                    <!-- Filter Toolbar -->
                    <form action="{{ route('posts.index') }}" method="GET" class="row g-2 mb-4 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search title, content, or author..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="all">Category: All</option>
                                <option value="Technology" {{ request('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Science" {{ request('category') == 'Science' ? 'selected' : '' }}>Science</option>
                                <option value="Design" {{ request('category') == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Business" {{ request('category') == 'Business' ? 'selected' : '' }}>Business</option>
                                <option value="Lifestyle" {{ request('category') == 'Lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                                <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Status: All</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published Only</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Drafts Only</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="views_desc" {{ request('sort') == 'views_desc' ? 'selected' : '' }}>Most Viewed</option>
                                <option value="views_asc" {{ request('sort') == 'views_asc' ? 'selected' : '' }}>Least Viewed</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-secondary w-100 fw-semibold">Filter</button>
                            @if(request()->hasAny(['search', 'category', 'status', 'sort']))
                                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="bi bi-x-lg"></i></a>
                            @endif
                        </div>
                    </form>

                    <!-- Posts Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td class="text-muted fw-bold font-monospace">{{ $post->id }}</td>
                                        <td>
                                            <a href="{{ route('posts.show', $post) }}" class="fw-bold text-decoration-none text-dark d-block">
                                                {{ Str::limit($post->title, 45) }}
                                            </a>
                                            <span class="text-muted small">{{ Str::limit(strip_tags($post->content), 60) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge-category category-{{ $post->category ?? 'General' }}">
                                                {{ $post->category ?? 'General' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $post->user?->name ?? 'N/A' }}</div>
                                            <div class="text-muted" style="font-size: 11px;">{{ $post->user?->email ?? '' }}</div>
                                        </td>
                                        <td>
                                            @if($post->is_published)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Published
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                                    <i class="bi bi-file-earmark me-1"></i> Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold {{ $post->views >= 5000 ? 'text-danger' : 'text-slate-700' }}">
                                            {{ number_format($post->views) }}
                                        </td>
                                        <td class="text-muted small">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete Post">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                            No posts found matching the filter criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts
                        </div>
                        <div>
                            {{ $posts->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal 1: Reset Baseline Confirmation -->
    <div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning-subtle text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-arrow-counterclockwise text-warning me-2"></i> Reset to Baseline Dataset?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-muted mb-3">This action will clear all current posts and generate <strong>10 clean baseline sample posts</strong> spanning multiple categories and realistic views for clean testing.</p>
                    <div class="alert alert-warning small text-start mb-0">
                        <i class="bi bi-info-circle me-1"></i> Admin user (admin@example.com) will be preserved.
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('posts.reset') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm fw-bold">Confirm &amp; Reset Baseline</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Purge All Confirmation -->
    <div class="modal fade" id="purgeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger-subtle text-danger border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-octagon text-danger me-2"></i> Purge All Fake Posts?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-trash3 text-danger fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark">Are you completely sure?</h6>
                    <p class="text-muted small">All records in the <code>posts</code> table will be permanently truncated. Users will remain intact.</p>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('posts.purge') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Yes, Purge Everything</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Studio JS Logic -->
    <script>
        // --- Slider Value Sync ---
        function updateSliderValue(val) {
            document.getElementById('sliderValueDisplay').innerText = val;
        }

        function setSliderValue(val) {
            document.getElementById('countRangeSlider').value = val;
            updateSliderValue(val);
        }

        // --- Tinker Snippet Injector ---
        function injectSnippet(code) {
            const editor = document.getElementById('tinkerCodeInput');
            editor.value = code;
            editor.focus();
        }

        // --- Tinker AJAX Runner ---
        function executeTinkerCode() {
            const code = document.getElementById('tinkerCodeInput').value.trim();
            const btn = document.getElementById('btnRunTinker');
            const wrapper = document.getElementById('tinkerResultsWrapper');
            const output = document.getElementById('tinkerOutputText');
            const statusBadge = document.getElementById('tinkerStatusBadge');
            const timeBadge = document.getElementById('tinkerExecutionTime');
            const queriesBadge = document.getElementById('tinkerQueriesCount');
            const queriesBox = document.getElementById('queriesListContainer');
            const queriesList = document.getElementById('sqlQueriesList');
            const sqlCountLabel = document.getElementById('sqlCountLabel');

            if (!code) {
                alert('Please enter a valid PHP or Eloquent snippet to execute.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Executing...';

            fetch("{{ route('posts.tinker') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ code: code })
            })
            .then(res => res.json())
            .then(data => {
                wrapper.style.display = 'block';
                timeBadge.innerText = data.execution_time_ms + ' ms';
                queriesBadge.innerText = (data.queries_count || 0) + ' Queries';

                if (data.success) {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.innerText = 'Executed (' + (data.result_type || 'Result') + ')';
                    output.innerText = typeof data.result === 'object' 
                        ? JSON.stringify(data.result, null, 2) 
                        : String(data.result);

                    // Render SQL Queries
                    if (data.queries && data.queries.length > 0) {
                        queriesBox.style.display = 'block';
                        sqlCountLabel.innerText = data.queries.length;
                        queriesList.innerHTML = '';
                        data.queries.forEach(q => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item bg-transparent text-secondary py-1 px-0';
                            li.innerHTML = `<span class="badge bg-secondary me-1">${q.time_ms}ms</span> ${q.query}`;
                            queriesList.appendChild(li);
                        });
                    } else {
                        queriesBox.style.display = 'none';
                    }
                } else {
                    statusBadge.className = 'badge bg-danger';
                    statusBadge.innerText = 'Evaluation Error';
                    output.innerText = data.error + (data.line ? '\nLine: ' + data.line : '');
                    queriesBox.style.display = 'none';
                }
            })
            .catch(err => {
                wrapper.style.display = 'block';
                statusBadge.className = 'badge bg-danger';
                statusBadge.innerText = 'Network / Server Error';
                output.innerText = 'Failed to connect to execution sandbox.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-play-fill me-1 text-warning"></i> Run Snippet';
            });
        }

        function copyTinkerOutput() {
            const text = document.getElementById('tinkerOutputText').innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Output copied to clipboard!');
            });
        }

        // --- Chart.js Initializer ---
        document.addEventListener('DOMContentLoaded', function () {
            const analyticsData = @json($analytics);

            // 1. Status Donut Chart
            new Chart(document.getElementById('chartStatus'), {
                type: 'doughnut',
                data: {
                    labels: analyticsData.status_chart.labels,
                    datasets: [{
                        data: analyticsData.status_chart.data,
                        backgroundColor: ['#10b981', '#94a3b8'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // 2. Category Donut Chart
            new Chart(document.getElementById('chartCategory'), {
                type: 'doughnut',
                data: {
                    labels: analyticsData.category_chart.labels,
                    datasets: [{
                        data: analyticsData.category_chart.data,
                        backgroundColor: ['#0284c7', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#f97316'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // 3. Views Histogram
            new Chart(document.getElementById('chartHistogram'), {
                type: 'bar',
                data: {
                    labels: analyticsData.views_histogram.labels,
                    datasets: [{
                        label: 'Posts Count',
                        data: analyticsData.views_histogram.data,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // 4. Monthly Publication Timeline Line Chart
            new Chart(document.getElementById('chartTimeline'), {
                type: 'line',
                data: {
                    labels: analyticsData.timeline_chart.labels,
                    datasets: [{
                        label: 'Posts Created',
                        data: analyticsData.timeline_chart.data,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.12)',
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#0284c7',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        });
    </script>
</body>
</html>