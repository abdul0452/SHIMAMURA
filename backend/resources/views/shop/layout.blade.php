<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko')</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f4f5f7;
            margin: 0;
            color: #1f2937;
        }
        .container { max-width: 900px; margin: 0 auto; padding: 24px 16px; }
        .navbar {
            background: #111827; color: #fff; padding: 16px 0;
        }
        .navbar .container { display: flex; align-items: center; justify-content: space-between; }
        .navbar a { color: #fff; text-decoration: none; font-weight: 600; }
        .card {
            background: #fff; border-radius: 12px; padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 16px;
        }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
        .product-name { font-weight: 600; font-size: 16px; margin: 0 0 4px; }
        .product-store { color: #6b7280; font-size: 13px; margin: 0 0 8px; }
        .product-price { font-weight: 700; color: #b45309; font-size: 18px; margin: 0 0 12px; }
        .btn {
            display: inline-block; background: #d97706; color: #fff; border: none;
            padding: 10px 16px; border-radius: 8px; font-weight: 600; cursor: pointer;
            text-decoration: none; font-size: 14px;
        }
        .btn:hover { background: #b45309; }
        .btn-block { width: 100%; text-align: center; }
        label { display: block; font-weight: 600; margin: 12px 0 4px; font-size: 14px; }
        input[type=text], input[type=email], input[type=tel], input[type=number] {
            width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;
        }
        .badge {
            display: inline-block; padding: 4px 10px; border-radius: 999px;
            font-size: 12px; font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .error { color: #dc2626; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="{{ route('shop.index') }}">🛒 Toko Kami</a>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
