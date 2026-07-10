<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laravel Site Search</title>
<style>
:root {
    --bg: #0f172a;
    --card-bg: rgba(30, 41, 59, 0.7);
    --input-bg: rgba(15, 23, 42, 0.8);
    --text: #ffffff;
    --text-secondary: #94a3b8;
    --accent: #38bdf8;
    --accent-hover: #0ea5e9;
    --border: #334155;
    --shadow: rgba(0, 0, 0, 0.4);
}

[data-theme="light"] {
    --bg: #e2e8f0;
    --card-bg: rgba(255, 255, 255, 0.65);
    --input-bg: rgba(255, 255, 255, 0.8);
    --text: #0f172a;
    --text-secondary: #475569;
    --accent: #0284c7;
    --accent-hover: #0369a1;
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
    font-family: Arial, sans-serif;
    color: var(--text);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
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

.card {
    width: 500px;
    max-width: 100%;
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 40px;
    border-radius: 20px;
    position: relative;
    box-shadow: 0 10px 25px var(--shadow);
    border: 1px solid var(--border);
    transition: background .3s, border .3s;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    color: var(--accent);
}

.input-wrapper {
    position: relative;
}

input {
    width: 100%;
    padding: 15px 45px 15px 15px;
    border: none;
    outline: none;
    border-radius: 10px;
    background: var(--input-bg);
    color: var(--text);
    font-size: 16px;
}

input::placeholder {
    color: var(--text-secondary);
}

.clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 20px;
    cursor: pointer;
    display: none;
    width: auto;
    padding: 0;
    margin: 0;
}

button[type="submit"] {
    width: 100%;
    padding: 15px;
    margin-top: 15px;
    border: none;
    border-radius: 10px;
    background: var(--accent);
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: .3s;
    color: #0f172a;
}

button[type="submit"]:hover {
    background: var(--accent-hover);
}

#suggestions {
    background: var(--input-bg);
    margin-top: 5px;
    border-radius: 10px;
    overflow: hidden;
}

.item {
    padding: 12px;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    transition: .3s;
}

.item:hover {
    background: var(--border);
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

@media(max-width:600px) {
    .card { padding: 25px; }
    h1 { font-size: 24px; }
}
</style>
</head>
<body>

<div class="theme-toggle" id="themeToggle">🌙</div>

<div class="card">
    <h1>Laravel Site Search</h1>

    <form action="{{ route('search') }}" method="GET">
        <div class="input-wrapper">
            <input
                type="text"
                name="query"
                id="search"
                placeholder="Search articles..."
                autocomplete="off"
                required
            >
            <button type="button" class="clear-btn" id="clearBtn">&times;</button>
        </div>

        <div id="suggestions"></div>

        <button type="submit">Search</button>
    </form>
</div>

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

const searchInput = document.getElementById('search');
const suggestionsBox = document.getElementById('suggestions');
const clearBtn = document.getElementById('clearBtn');

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

searchInput.addEventListener('input', function () {
    clearBtn.style.display = this.value.length > 0 ? 'block' : 'none';
});

searchInput.addEventListener('keyup', function () {
    let value = this.value;

    if (value.length < 1) {
        suggestionsBox.innerHTML = '';
        return;
    }

    fetch("{{ route('suggestions') }}?search=" + encodeURIComponent(value))
        .then(response => response.json())
        .then(data => {
            let html = '';

            data.forEach(item => {
                html += `<div class="item">${escapeHtml(item)}</div>`;
            });

            suggestionsBox.innerHTML = html;

            document.querySelectorAll('.item').forEach(item => {
                item.onclick = function () {
                    searchInput.value = this.innerText;
                    suggestionsBox.innerHTML = '';
                    clearBtn.style.display = 'block';
                };
            });
        });
});

clearBtn.addEventListener('click', function () {
    searchInput.value = '';
    suggestionsBox.innerHTML = '';
    clearBtn.style.display = 'none';
    searchInput.focus();
    showToast('Search cleared');
});
</script>

</body>
</html>