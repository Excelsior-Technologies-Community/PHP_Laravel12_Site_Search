<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laravel Site Search</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0f172a;
            font-family: Arial, sans-serif;
            color: white;

            display: flex;
            justify-content: center;
            align-items: center;

            min-height: 100vh;
            padding: 20px;
        }

        .card {
            width: 500px;
            max-width: 100%;

            background: #1e293b;

            padding: 40px;

            border-radius: 20px;

            position: relative;

            box-shadow:
                0 10px 25px rgba(0,0,0,.4);
        }

        h1 {
            text-align: center;

            margin-bottom: 30px;

            color: #38bdf8;
        }

        input {
            width: 100%;

            padding: 15px;

            border: none;
            outline: none;

            border-radius: 10px;

            background: #0f172a;

            color: white;

            font-size: 16px;
        }

        input::placeholder {
            color: #94a3b8;
        }

        button {
            width: 100%;

            padding: 15px;

            margin-top: 15px;

            border: none;

            border-radius: 10px;

            background: #38bdf8;

            cursor: pointer;

            font-size: 16px;
            font-weight: bold;

            transition: .3s;
        }

        button:hover {
            background: #0ea5e9;
        }

        #suggestions {
            background: #0f172a;

            margin-top: 5px;

            border-radius: 10px;

            overflow: hidden;
        }

        .item {
            padding: 12px;

            cursor: pointer;

            border-bottom:
                1px solid #334155;

            transition: .3s;
        }

        .item:hover {
            background: #334155;
        }

        @media(max-width:600px) {

            .card {
                padding: 25px;
            }

            h1 {
                font-size: 24px;
            }

        }
    </style>

</head>

<body>

    <div class="card">

        <h1>
            Laravel Site Search
        </h1>

        <form action="{{ route('search') }}" method="GET">

            <input
                type="text"
                name="query"
                id="search"
                placeholder="Search articles..."
                autocomplete="off"
                required
            >

            <div id="suggestions"></div>

            <button type="submit">
                Search
            </button>

        </form>

    </div>


    <script>

        const searchInput =
            document.getElementById('search');

        const suggestionsBox =
            document.getElementById('suggestions');


        searchInput.addEventListener(
            'keyup',

            function () {

                let value = this.value;


                if (value.length < 1) {

                    suggestionsBox.innerHTML = '';

                    return;
                }


                fetch(
                    "{{ route('suggestions') }}?search=" + value
                )

                .then(
                    response => response.json()
                )

                .then(data => {

                    let html = '';

                    data.forEach(item => {

                        html += `

                            <div class="item">
                                ${item}
                            </div>

                        `;

                    });


                    suggestionsBox.innerHTML = html;


                    document
                    .querySelectorAll('.item')

                    .forEach(item => {

                        item.onclick = function () {

                            searchInput.value =
                                this.innerText;

                            suggestionsBox.innerHTML='';

                        };

                    });

                });

            });

    </script>

</body>
</html>