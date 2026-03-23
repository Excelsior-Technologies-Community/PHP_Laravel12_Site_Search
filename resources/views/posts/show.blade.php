<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .post-card {
            background: #1e293b;
            padding: 30px 35px;
            border-radius: 20px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s;
        }

        .post-card:hover {
            transform: translateY(-3px);
        }

        h1 {
            color: #38bdf8;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 20px;
            color: #cbd5e1;
        }

        a.back {
            color: #facc15;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
        }

        a.back:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .post-card {
                padding: 20px;
                border-radius: 16px;
            }

            p {
                font-size: 14px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="post-card">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->body }}</p>

        <div style="text-align:center;">
            <a href="{{ $query ? route('search', ['query' => $query]) : url('/') }}" class="back">Back to Search Result</a>
        </div>
    </div>
</body>

</html>