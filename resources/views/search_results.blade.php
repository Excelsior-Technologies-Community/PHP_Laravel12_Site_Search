<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Search Results</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#0f172a;
            color:white;
            font-family:Arial,sans-serif;
            padding:40px;
        }

        h1{
            color:#38bdf8;
            text-align:center;
            margin-bottom:20px;
        }

        .count{
            text-align:center;
            margin:20px 0;
            color:#94a3b8;
        }

        .trend-box{
            background:#1e293b;
            padding:20px;
            border-radius:15px;
            margin-bottom:30px;
            text-align:center;
        }

        .trend-box h3{
            margin-bottom:15px;
        }

        .tag{
            display:inline-block;
            padding:10px 15px;
            margin:5px;
            background:#38bdf8;
            color:black;
            border-radius:30px;
            font-weight:bold;
        }

        .card{
            background:#1e293b;
            padding:20px;
            margin-bottom:20px;
            border-radius:15px;
            transition:.3s;
        }

        .card:hover{
            transform:translateY(-3px);
        }

        .card a{
            color:#38bdf8;
            font-size:22px;
            text-decoration:none;
            font-weight:bold;
        }

        .card p{
            margin-top:12px;
            line-height:1.7;
            color:#cbd5e1;
        }

        mark{
            background:yellow;
            padding:2px;
        }

        .no-result{
            text-align:center;
            color:#ef4444;
            margin-top:40px;
        }

        .back{
            color:#facc15;
            display:block;
            text-align:center;
            margin-top:30px;
            text-decoration:none;
            font-weight:bold;
        }

        .back:hover{
            text-decoration:underline;
        }

        @media(max-width:768px){

            body{
                padding:20px;
            }

            .card{
                padding:15px;
            }

            .card a{
                font-size:18px;
            }

        }
    </style>

</head>

<body>

    <h1>
        Search : "{{ $query }}"
    </h1>


    <div class="count">
        {{ $results->count() }} results found
    </div>


    <div class="trend-box">

        <h3>
            🔥 Trending Searches
        </h3>

        @foreach($trending as $trend)

            <span class="tag">
                {{ $trend->keyword }}
                ({{ $trend->count }})
            </span>

        @endforeach

    </div>


    @if($results->isEmpty())

        <h2 class="no-result">
            No Results Found
        </h2>

    @endif


    @foreach($results as $post)

        <div class="card">

            <a href="{{ route('posts.show', ['id' => $post->id,'query'=>$query]) }}">
                {{ $post->title }}
            </a>

            <p>
                {!! str_ireplace(
                    $query,
                    "<mark>$query</mark>",
                    Illuminate\Support\Str::limit(
                        $post->body,
                        200
                    )
                ) !!}
            </p>

        </div>

    @endforeach


    <a href="/" class="back">
        Back To Search
    </a>

</body>

</html>