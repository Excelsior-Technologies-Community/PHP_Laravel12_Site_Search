<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 40px;
        }

        h1 {
            color: #38bdf8;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }

        .card {
            background: #1e293b;
            padding: 25px 30px;
            margin: 20px auto;
            border-radius: 16px;
            max-width: 700px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.7);
        }

        a {
            color: #38bdf8;
            text-decoration: none;
            font-size: 22px;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .snippet {
            color: #cbd5e1;
            margin-top: 12px;
            font-size: 16px;
            line-height: 1.6;
        }

        .no-results {
            color: #f87171;
            font-weight: bold;
            text-align: center;
        }

        .back {
            color: #facc15;
            display: inline-block;
            margin-top: 35px;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .card {
                padding: 20px;
                border-radius: 12px;
                margin: 15px;
            }

            a {
                font-size: 18px;
            }

            .snippet {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <h1>Search Results for "{{ $query }}"</h1>

    @if($results->isEmpty())
        <p class="no-results">No results found.</p>
    @else
        @foreach($results as $post)
            <div class="card">
                <a href="{{ route('posts.show', ['id' => $post->id, 'query' => $query]) }}">
                    {{ $post->title }}
                </a>
                <p class="snippet">{{ \Illuminate\Support\Str::limit($post->body, 200) }}</p>
            </div>
        @endforeach
    @endif

    <div style="text-align:center;">
        <a href="{{ url('/') }}" class="back">Back to Search</a>
    </div>
</body>

</html>