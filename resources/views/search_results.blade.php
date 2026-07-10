<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Results</title>
<style>
:root {
    --bg: #0f172a;
    --card-bg: rgba(30, 41, 59, 0.7);
    --text: #ffffff;
    --text-secondary: #94a3b8;
    --accent: #38bdf8;
    --accent-hover: #0ea5e9;
    --border: #334155;
    --shadow: rgba(0, 0, 0, 0.4);
    --danger: #ef4444;
    --tag: #38bdf8;
}

[data-theme="light"] {
    --bg: #e2e8f0;
    --card-bg: rgba(255, 255, 255, 0.65);
    --text: #0f172a;
    --text-secondary: #475569;
    --accent: #0284c7;
    --accent-hover: #0369a1;
    --border: #cbd5e1;
    --shadow: rgba(0, 0, 0, 0.15);
    --danger: #dc2626;
    --tag: #0284c7;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: var(--bg);
    color: var(--text);
    font-family: Arial, sans-serif;
    padding: 40px;
    transition: background .3s, color .3s;
}

.theme-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--card-bg);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 22px;
    box-shadow: 0 5px 15px var(--shadow);
    transition: .3s;
    z-index: 999;
}

.theme-toggle:hover {
    transform: scale(1.08);
}

h1 {
    color: var(--accent);
    text-align: center;
    margin-bottom: 20px;
}

.count {
    text-align: center;
    margin: 20px 0;
    color: var(--text-secondary);
}

.trend-box {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    text-align: center;
}

.trend-box h3 {
    margin-bottom: 15px;
}

.tag {
    display: inline-block;
    padding: 10px 15px;
    margin: 5px;
    background: var(--tag);
    color: #0f172a;
    border-radius: 30px;
    font-weight: bold;
}

.card {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 15px;
    transition: transform .3s, background .3s;
}

.card:hover {
    transform: translateY(-3px);
}

.card a {
    color: var(--accent);
    font-size: 22px;
    text-decoration: none;
    font-weight: bold;
}

.card p {
    margin-top: 12px;
    line-height: 1.7;
    color: var(--text-secondary);
}

mark {
    background: yellow;
    color: #0f172a;
    padding: 2px;
    border-radius: 3px;
}

.no-result {
    text-align: center;
    color: var(--danger);
    margin-top: 40px;
}

.back {
    color: #facc15;
    display: block;
    text-align: center;
    margin-top: 30px;
    text-decoration: none;
    font-weight: bold;
}

.back:hover {
    text-decoration: underline;
}

.pagination {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 30px;
}

.page-btn {
    padding: 10px 15px;
    border-radius: 8px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    color: var(--text);
    text-decoration: none;
    font-size: 14px;
    transition: .3s;
}

.page-btn:hover {
    background: var(--accent);
    color: #0f172a;
}

.page-btn.active {
    background: var(--accent);
    color: #0f172a;
    font-weight: bold;
}

.page-btn.disabled {
    opacity: .4;
    pointer-events: none;
}

.toast-container {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 1000;
}

.toast {
    background: var(--card-bg);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 14px 22px;
    border-radius: 10px;
    box-shadow: 0 5px 15px var(--shadow);
    font-size: 14px;
    animation: toastIn .3s ease forwards;
}

.toast.hide {
    animation: toastOut .3s ease forwards;
}

@keyframes toastIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes toastOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(20px); }
}

@media(max-width:768px) {
    body { padding: 20px; }
    .card { padding: 15px; }
    .card a { font-size: 18px; }
}
</style>
</head>
<body>

<div class="theme-toggle" id="themeToggle">🌙</div>

<h1>Search : "{{ $query }}"</h1>

<div class="count">{{ $results->count() }} results found</div>

<div class="trend-box">
    <h3>🔥 Trending Searches</h3>

    @foreach($trending as $trend)
        <span class="tag">{{ $trend->keyword }} ({{ $trend->count }})</span>
    @endforeach
</div>

@if($results->isEmpty())
    <h2 class="no-result">No Results Found</h2>
@endif

@foreach($results as $post)
    @php
        $safeQuery = e($query);
        $safeBody = e(Illuminate\Support\Str::limit($post->body, 200));
        $highlightedBody = $safeQuery !== ''
            ? preg_replace('/(' . preg_quote($safeQuery, '/') . ')/i', '<mark>$1</mark>', $safeBody)
            : $safeBody;
    @endphp

    <div class="card">
        <a href="{{ route('posts.show', ['id' => $post->id, 'query' => $query]) }}">
            {{ $post->title }}
        </a>

        <p>{!! $highlightedBody !!}</p>
    </div>
@endforeach

@if ($results instanceof Illuminate\Pagination\LengthAwarePaginator && $results->hasPages())
    <div class="pagination">
        @if ($results->onFirstPage())
            <span class="page-btn disabled">Prev</span>
        @else
            <a href="{{ $results->previousPageUrl() }}" class="page-btn">Prev</a>
        @endif

        @for ($i = 1; $i <= $results->lastPage(); $i++)
            <a href="{{ $results->url($i) }}" class="page-btn {{ $i == $results->currentPage() ? 'active' : '' }}">{{ $i }}</a>
        @endfor

        @if ($results->hasMorePages())
            <a href="{{ $results->nextPageUrl() }}" class="page-btn">Next</a>
        @else
            <span class="page-btn disabled">Next</span>
        @endif
    </div>
@endif

<a href="/" class="back">Back To Search</a>

<div class="toast-container" id="toastContainer"></div>

<script>
(function () {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
})();

const themeToggle = document.getElementById('themeToggle');

function updateToggleIcon() {
    const theme = document.documentElement.getAttribute('data-theme');
    themeToggle.textContent = theme === 'light' ? '☀️' : '🌙';
}

updateToggleIcon();

themeToggle.addEventListener('click', function () {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateToggleIcon();
});

function showToast(message) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(function () {
        toast.classList.add('hide');
        setTimeout(function () {
            toast.remove();
        }, 300);
    }, 2500);
}

@if($results->isEmpty() && $query !== '')
    showToast('No articles found for "{{ $query }}"');
@endif
</script>

</body>
</html>