# PHP_Laravel12_Site_Search


## Project Description

PHP_Laravel12_Site_Search is a modern Laravel 12 application designed to implement site-wide search functionality for posts or articles. 

Users can search by keywords, view relevant results, and navigate to detailed post pages. 

This project is beginner-friendly and demonstrates how to integrate a search package (Spatie Laravel Site Search) with Laravel while maintaining a clean, responsive, and dark-mode-friendly UI.



## Purpose:

- To enable a fast and efficient search across posts stored in a database.
- To demonstrate Laravel 12’s MVC architecture with models, controllers, and views.
- To provide a responsive UI that works on both desktop and mobile devices.
- To preserve the search query between pages for better user experience.



## Features

1. Search Functionality – Users can search posts by title or body.
2. Search Results Page – Displays matching posts with snippets and clickable links.
3. Post Details Page – Shows full post content and a back button to search results.
4. Responsive UI – Works on desktop and mobile, with hover effects for cards and buttons.
5. Dark Mode – Elegant dark-themed interface for better readability.
6. Preserve Search Query – Keeps the search input across pages for user convenience.
7. Database Seeding – Pre-populated posts to test search functionality.
8. Clean MVC Structure – Models, Controllers, and Views separated according to Laravel best practices.



## Technology Stack

- Backend: PHP 8.2+, Laravel 12
- Frontend: Blade templates, HTML, CSS (responsive & dark-mode)
- Database: MySQL
- Packages:
- Spatie Laravel Site Search: Handles searchable models and returns results with clickable links.
- Web Server: Laravel Artisan (php artisan serve) for local development




---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Site_Search "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Site_Search

```

#### Explanation:

Installs a fresh Laravel 12 application in the PHP_Laravel12_Site_Search folder.

Moves into the project directory to run further commands.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Site_Search
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Site_Search

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel to MySQL and creates default tables for the project.




## STEP 3: Install Spatie Site Search Package

### Run:

```
composer require spatie/laravel-site-search

```

#### Explanation: 

Adds the package for site search functionality.





## STEP 4: Publish Config

### Run:

```
php artisan vendor:publish --provider="Spatie\SiteSearch\SiteSearchServiceProvider" --tag="site-search-config"

```


#### Explanation:

Creates config/site-search.php for customizing search settings.






## STEP 5: Create a Searchable Model

### Create Model + Migration

```
php artisan make:model Post -m

```


### Open database/migrations/xxxx_create_posts_table.php:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```

### Then Run:

```
php artisan migrate

```


### Open app/Models/Post.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Post extends Model implements Searchable
{
    protected $fillable = ['title', 'body'];

    // Spatie will call this to get search result info
    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->title,
            route('posts.show', $this->id) // clickable link
        );
    }
}

```

#### Explanation: 

Creates Post model and migration to store post data.

Makes Post searchable and clickable to its details page.




## STEP 6: Update Config

### Open: config/site-search.php

```
'models' => [
    App\Models\Post::class,
],

```

#### Explanation: 

Tells the package which model(s) to search in.





## STEP 7: Seed Some Posts

### Create a seeder:

```
php artisan make:seeder PostSeeder

```

### Open: database/seeders/PostSeeder.php:

```
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'title' => 'Learn Laravel Easily',
            'body' => 'Laravel is a great PHP framework for beginners and professionals.',
        ]);

        Post::create([
            'title' => 'Laravel Site Search Example',
            'body' => 'This tutorial shows how to add site search to your Laravel app.',
        ]);

        Post::create([
            'title' => 'Advanced PHP Techniques',
            'body' => 'Learn advanced PHP topics and improve your coding skills.',
        ]);
    }
}

```

### Run:

```
php artisan db:seed --class=PostSeeder

```

#### Explanation: 

Adds sample posts to test the search functionality.






## STEP 8: Create Controller

### Run:

```
php artisan make:controller SearchController

php artisan make:controller SearchController

```


### Open: app/Http/Controllers/SearchController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query', '');

        if (empty($query)) {
            return view('search_results', [
                'results' => collect(),
                'query' => ''
            ]);
        }

        // Only fetch posts that contain the exact phrase in title OR body
        $results = Post::where('title', 'like', "%{$query}%")
            ->orWhere('body', 'like', "%{$query}%")
            ->get();

        return view('search_results', compact('results', 'query'));
    }
}

```


### Open: app/Http/Controllers/PostController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
   public function show(Request $request, $id)
{
    $post = Post::findOrFail($id);
    $query = $request->input('query'); // preserve search query
    return view('posts.show', compact('post', 'query'));
}
}

```

#### Explanation: 

Controllers handle search logic and post details view.

Shows single post details and preserves search query.





## STEP 9: Add Routes

### Open: routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('welcome'); // home page with search form
});

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

```

#### Explanation: 

Defines home, search, and post details routes.




## STEP 10: Create Search Form View

### resources/views/search_results.blade.php

```
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

```


### resources/views/welcome.blade.php

```
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

```



### resources/views/posts/show.blade.php

```
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

```

#### Explanation: 

Blade templates handle UI and pass query between pages.





## STEP 11: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts the local development server.




## Expected Output:

### Main Page:


<img src="screenshots/Screenshot 2026-03-23 170745.png" width="900">


### Search Site:


<img src="screenshots/Screenshot 2026-03-23 182310.png" width="900">


### Search Results Page:


<img src="screenshots/Screenshot 2026-03-23 182321.png" width="900">


### Post Details Page:


<img src="screenshots/Screenshot 2026-03-23 182330.png" width="900">




---

## Project Folder Structure:

```
PHP_Laravel12_Site_Search/
│
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PostController.php
│   │   │   └── SearchController.php
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Models/
│   │   └── Post.php
│   ├── Providers/
│   └── ...
│
├── bootstrap/
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── site-search.php      <-- Spatie site search config
│
├── database/
│   ├── migrations/
│   │   └── xxxx_create_posts_table.php
│   ├── seeders/
│   │   └── PostSeeder.php
│   └── factories/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── views/
│   │   ├── welcome.blade.php         <-- Home / Search form
│   │   ├── search_results.blade.php  <-- Search Results page
│   │   └── posts/
│   │       └── show.blade.php        <-- Post details page
│   └── css/                          <-- optional CSS if separated
│
├── routes/
│   └── web.php                        <-- Routes file
│
├── storage/
├── tests/
├── vendor/
│
├── artisan
├── composer.json
├── composer.lock
└── .env

```
