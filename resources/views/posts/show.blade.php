<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $post->title }}</title>
<style>
:root {
    --bg: #0f172a;
    --card-bg: rgba(30, 41, 59, 0.7);
    --text: #ffffff;
    --text-secondary: #cbd5e1;
    --accent: #38bdf8;
    --border: #334155;
    --shadow: rgba(0, 0, 0, 0.6);
}

[data-theme="light"] {
    --bg: #e2e8f0;
    --card-bg: rgba(255, 255, 255, 0.65);
    --text: #0f172a;
    --text-secondary: #334155;
    --accent: #0284c7;
    --border: #cbd5e1;
    --shadow: rgba(0, 0, 0, 0.15);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: var(--bg);
    color: var(--text);
    font-family: 'Segoe UI', Arial, sans-serif;
    padding: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
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

.post-card {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    padding: 30px 35px;
    border-radius: 20px;
    max-width: 700px;
    width: 100%;
    box-shadow: 0 8px 20px var(--shadow);
    transition: transform .3s, background .3s;
}

.post-card:hover {
    transform: translateY(-3px);
}

h1 {
    color: var(--accent);
    margin-bottom: 20px;
    text-align: center;
}

p {
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 20px;
    color: var(--text-secondary);
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
    .post-card { padding: 20px; border-radius: 16px; }
    p { font-size: 14px; }
    h1 { font-size: 24px; }
}
</style>
</head>
<body>

<div class="theme-toggle" id="themeToggle">🌙</div>

<div class="post-card">
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->body }}</p>

    <div style="text-align:center;">
        <a href="{{ $query ? route('search', ['query' => $query]) : url('/') }}" class="back">Back to Search Result</a>
    </div>
</div>

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
</script>

</body>
</html>