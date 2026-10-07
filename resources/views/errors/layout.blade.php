<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — Something went wrong</title>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; background: #071527; color: #fff;
               min-height: 100vh; margin: 0; display: grid; place-items: center; padding: 2rem; }
        .box { max-width: 40rem; text-align: center; }
        h1 { font-size: clamp(2.5rem, 10vw, 4.5rem); margin: 0 0 0.5rem; color: #FFC72C; }
        p { color: rgba(255,255,255,0.8); line-height: 1.6; }
        a { display: inline-block; margin-top: 1.5rem; background: #FFC72C; color: #071527;
            padding: 0.85rem 1.75rem; border-radius: 999px; font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h1>@yield('code')</h1>
        <h2>@yield('heading')</h2>
        <p>@yield('message')</p>
        <a href="/">Back to the homepage</a>
    </div>
</body>
</html>