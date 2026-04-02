<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ $systemSettings->shop_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-1: #f7f1e7;
            --bg-2: #efe4d1;
            --card-bg: rgba(255,255,255,.92);
            --text: #1f2a37;
            --muted: #6b7280;
            --accent: #b98944;
            --accent-dark: #8e6630;
            --border: rgba(143,102,48,.16);
            --shadow: 0 24px 60px rgba(24,38,51,.12);
        }
        [data-theme="dark"] {
            --bg-1: #101821;
            --bg-2: #1b2734;
            --card-bg: rgba(24,33,44,.96);
            --text: #edf2f7;
            --muted: #9eb0c4;
            --accent: #f2c98a;
            --accent-dark: #d3a963;
            --border: rgba(242,201,138,.16);
            --shadow: 0 24px 60px rgba(0,0,0,.34);
        }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at top right, rgba(185,137,68,.18), transparent 28%),
                linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 100%);
            color: var(--text);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .login-shell { width: min(460px, calc(100vw - 2rem)); }
        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 1.35rem;
            box-shadow: var(--shadow);
            padding: 1.25rem;
            backdrop-filter: blur(12px);
        }
        .brand-mark {
            width: 4rem;
            height: 4rem;
            border-radius: 1.1rem;
            background: #fff;
            display: grid;
            place-items: center;
            box-shadow: 0 18px 36px rgba(24,38,51,.12);
            overflow: hidden;
        }
        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: .35rem;
        }
        .eyebrow {
            font-size: .76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: var(--accent-dark);
            margin-bottom: .35rem;
        }
        .form-control,
        .form-check-input {
            border-color: var(--border);
        }
        .form-control {
            min-height: 2.9rem;
            border-radius: .9rem;
            background: rgba(255,255,255,.9);
        }
        [data-theme="dark"] .form-control {
            background: rgba(16,25,34,.92);
            color: var(--text);
        }
        .btn-login {
            min-height: 2.9rem;
            border-radius: .95rem;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            border: 0;
            color: #fff;
            font-weight: 700;
        }
        .btn-login:hover,
        .btn-login:focus { color: #fff; }
        .theme-toggle {
            border-radius: 999px;
            border: 1px solid var(--border);
            color: var(--text);
            min-width: 2.5rem;
        }
        .helper {
            color: var(--muted);
            font-size: .92rem;
        }
    </style>
    <script>
        (function () {
            var savedTheme = localStorage.getItem('tailor-theme');
            document.documentElement.setAttribute('data-theme', savedTheme === 'dark' ? 'dark' : 'light');
        })();
    </script>
</head>
<body>
    <main class="login-shell">
        <section class="login-card">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="brand-mark"><img src="{{ asset($systemSettings->logo_path ?: 'images/shaq-logo.png') }}" alt="{{ $systemSettings->shop_name }} logo"></span>
                    <div>
                        <p class="eyebrow">Tailor Shop Login</p>
                        <h1 class="h3 mb-1">{{ $systemSettings->shop_name }}</h1>
                        <p class="helper mb-0">{{ $systemSettings->shop_tagline }}</p>
                    </div>
                </div>
                <button type="button" class="btn theme-toggle" id="theme-toggle" aria-label="Toggle light and dark theme" title="Switch to dark mode">&#9789;</button>
            </div>

            <p class="helper mb-4">Sign in to access customers, measurements, orders, and payments.</p>

            <form method="POST" action="{{ route('login.store') }}" class="d-grid gap-3">
                @csrf
                <div>
                    <label for="email" class="form-label small text-uppercase fw-semibold">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="password" class="form-label small text-uppercase fw-semibold">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label helper" for="remember">Keep me signed in</label>
                </div>
                <button type="submit" class="btn btn-login">Sign In</button>
            </form>
        </section>
    </main>
    <script>
        (function () {
            var root = document.documentElement;
            var toggleButton = document.getElementById('theme-toggle');

            function applyTheme(theme) {
                var isDark = theme === 'dark';

                root.setAttribute('data-theme', theme);
                toggleButton.innerHTML = isDark ? '&#9728;' : '&#9789;';
                toggleButton.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            }

            applyTheme(root.getAttribute('data-theme') || 'light');

            toggleButton.addEventListener('click', function () {
                var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                localStorage.setItem('tailor-theme', nextTheme);
                applyTheme(nextTheme);
            });
        })();
    </script>
</body>
</html>
