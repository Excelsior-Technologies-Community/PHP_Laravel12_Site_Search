<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Site Search</title>
    <style>
        body {
            background: #0f172a;
            color: #ffffff;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        h1 {
            color: #38bdf8;
            font-size: 36px;
            margin-bottom: 40px;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }

        .search-card {
            background: #1e293b;
            padding: 40px 50px;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            text-align: center;
            transition: transform 0.3s;
        }

        .search-card:hover {
            transform: translateY(-5px);
        }

        input[type=text] {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 12px;
            border: none;
            font-size: 16px;
            outline: none;
            background: #0f172a;
            color: #ffffff;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.5);
        }

        input[type=text]::placeholder {
            color: #94a3b8;
        }

        button {
            width: 100%;
            padding: 14px 0;
            background: linear-gradient(90deg, #38bdf8, #0ea5e9);
            color: #0f172a;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(90deg, #0ea5e9, #38bdf8);
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            .search-card {
                padding: 30px 20px;
                border-radius: 16px;
            }
        }
    </style>
</head>

<body>
    <h1>Laravel Site Search</h1>
    <div class="search-card">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" name="query" placeholder="Type to search..." value="{{ request('query') }}" required>
            <button type="submit">Search</button>
        </form>
    </div>
</body>

</html>