<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $post->title }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: "Segoe UI", sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #0d6efd, #4f8dfd);
            color: white;
            border-radius: 18px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(13,110,253,.2);
        }

        .page-header h1{
            font-size:32px;
            font-weight:700;
            margin-bottom:10px;
        }

        .page-header p{
            margin:0;
            opacity:.9;
        }

        .info-card{
            background:#fff;
            border:none;
            border-radius:18px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            transition:.3s;
            height:100%;
        }

        .info-card:hover{
            transform:translateY(-5px);
        }

        .info-card .card-body{
            padding:25px;
        }

        .icon-box{
            width:60px;
            height:60px;
            border-radius:15px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:26px;
            color:#fff;
            margin-bottom:18px;
        }

        .bg-blue{
            background:#0d6efd;
        }

        .bg-success-custom{
            background:#198754;
        }

        .bg-warning-custom{
            background:#ffc107;
            color:#212529;
        }

        .bg-danger-custom{
            background:#dc3545;
        }

        .label{
            color:#6c757d;
            font-size:14px;
            margin-bottom:6px;
        }

        .value{
            font-size:20px;
            font-weight:700;
            color:#212529;
        }

        .content-card{
            background:#fff;
            border:none;
            border-radius:18px;
            margin-top:30px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        .content-header{
            background:#f8f9fa;
            border-bottom:1px solid #ececec;
            padding:20px 25px;
            border-radius:18px 18px 0 0;
            font-weight:700;
            font-size:22px;
        }

        .content-body{
            padding:30px;
            font-size:17px;
            line-height:1.9;
            color:#495057;
            white-space:pre-line;
        }

        .status-badge{
            font-size:14px;
            padding:10px 18px;
            border-radius:30px;
        }

        .btn-back{
            border-radius:12px;
            padding:10px 25px;
        }
    </style>

</head>

<body>

<div class="container py-5">

    <!-- Header -->

    <div class="page-header">

        <h1>
            <i class="bi bi-file-earmark-text-fill"></i>
            {{ $post->title }}
        </h1>

        <p>
            Complete details of the selected post.
        </p>

    </div>

    <!-- Information Cards -->

    <div class="row g-4">

        <div class="col-lg-3 col-md-6">

            <div class="card info-card">

                <div class="card-body">

                    <div class="icon-box bg-blue">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div class="label">
                        Author
                    </div>

                    <div class="value">
                        {{ $post->user->name ?? $post->author }}
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card info-card">

                <div class="card-body">

                    <div class="icon-box bg-success-custom">
                        <i class="bi bi-eye-fill"></i>
                    </div>

                    <div class="label">
                        Total Views
                    </div>

                    <div class="value">
                        {{ number_format($post->views) }}
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card info-card">

                <div class="card-body">

                    <div class="icon-box bg-warning-custom">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>

                    <div class="label">
                        Published On
                    </div>

                    <div class="value" style="font-size:17px;">
                        {{ optional($post->published_at)->format('d M Y') ?? 'Not Published' }}
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card info-card">

                <div class="card-body">

                    <div class="icon-box bg-danger-custom">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div class="label">
                        Status
                    </div>

                    @if($post->is_published)

                        <span class="badge bg-success status-badge">
                            Published
                        </span>

                    @else

                        <span class="badge bg-danger status-badge">
                            Unpublished
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <!-- Content -->

    <div class="card content-card">

        <div class="content-header">

            <i class="bi bi-journal-text text-primary"></i>

            Post Content

        </div>

        <div class="content-body">

            {{ $post->content }}

        </div>

    </div>

    <!-- Buttons -->

    <div class="mt-4 d-flex justify-content-between">

        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-back">

            <i class="bi bi-arrow-left-circle"></i>

            Back to Dashboard

        </a>

        <span class="text-muted align-self-center">

            Post ID : <strong>#{{ $post->id }}</strong>

        </span>

    </div>

</div>

</body>
</html>