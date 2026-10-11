<!DOCTYPE html>
{{-- 宣告頁面使用繁體中文，協助瀏覽器與輔助工具判斷語言。 --}}
<html lang="zh-TW">
<head>
    {{-- 使用 UTF-8，才能正確顯示中文。 --}}
    <meta charset="UTF-8">
    {{-- 讓頁面在手機上使用正確寬度與縮放比例。 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- 瀏覽器分頁上顯示的標題。 --}}
    <title>登入</title>
    <style>
        * { box-sizing: border-box; }
        :root {
            --orange-950: #431407;
            --orange-800: #9a3412;
            --orange-700: #c2410c;
            --orange-500: #f97316;
            --orange-200: #fed7aa;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: system-ui, sans-serif;
            background:
                radial-gradient(circle at 18% 16%, rgb(255 255 255 / 58%) 0 10%, transparent 32%),
                radial-gradient(circle at 84% 82%, rgb(194 65 12 / 18%) 0 12%, transparent 36%),
                linear-gradient(145deg, #fff7ed 0%, #fed7aa 46%, #fb923c 100%);
            color: var(--orange-950);
        }
        main {
            width: min(100%, 420px);
            padding: 34px 36px 36px;
            border: 1px solid rgb(255 255 255 / 72%);
            border-radius: 24px;
            background: rgb(255 250 244 / 92%);
            box-shadow: 0 24px 70px rgb(124 45 18 / 22%);
            backdrop-filter: blur(10px);
        }
        .brand { margin-bottom: 24px; text-align: center; }
        .brand-icon {
            display: block;
            width: 104px;
            height: 104px;
            margin: -4px auto 12px;
            object-fit: contain;
            filter: drop-shadow(0 12px 14px rgb(194 65 12 / 22%));
        }
        h1 {
            margin: 0;
            color: var(--orange-950);
            font-size: 1.75rem;
            line-height: 1.2;
            letter-spacing: .04em;
        }
        .subtitle { margin: 8px 0 0; color: var(--orange-800); font-size: .92rem; }
        label {
            display: block;
            margin-top: 16px;
            color: #7c2d12;
            font-size: .9rem;
            font-weight: 700;
        }
        input {
            width: 100%;
            margin-top: 7px;
            padding: 12px 14px;
            border: 1px solid var(--orange-200);
            border-radius: 11px;
            background: white;
            color: var(--orange-950);
            font: inherit;
            transition: border-color .2s, box-shadow .2s;
        }
        input:focus {
            outline: none;
            border-color: var(--orange-500);
            box-shadow: 0 0 0 4px rgb(249 115 22 / 16%);
        }
        button {
            width: 100%;
            margin-top: 24px;
            padding: 12px;
            border: 0;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-700));
            color: white;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 22px rgb(234 88 12 / 25%);
            transition: transform .2s, box-shadow .2s;
        }
        button:hover { transform: translateY(-1px); box-shadow: 0 13px 26px rgb(234 88 12 / 32%); }
        button:focus-visible { outline: 3px solid var(--orange-200); outline-offset: 3px; }
        .error {
            margin: 0 0 12px;
            padding: 10px 12px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: .9rem;
        }
        @media (max-width: 480px) {
            body { padding: 16px; }
            main { padding: 28px 22px 26px; border-radius: 20px; }
            .brand-icon { width: 90px; height: 90px; }
        }
    </style>
</head>
<body>
    {{-- main 包住這個頁面的主要內容。 --}}
    <main>
        <header class="brand">
            <img class="brand-icon" src="{{ asset('images/shop-sun-login-icon.png') }}" alt="暖陽店舖圖標">
            <h1>店家登入</h1>
            <p class="subtitle">歡迎回來，開始管理你的店舖</p>
        </header>

        {{-- Laravel 若帶回任何驗證錯誤，就顯示第一則錯誤訊息。 --}}
        @if ($errors->any())
            {{-- role="alert" 可讓輔助工具知道這是重要錯誤訊息。 --}}
            <p class="error" role="alert">{{ $errors->first() }}</p>
        @endif

        {{-- 表單使用 POST 送到名為 login.store 的路由。 --}}
        <form method="POST" action="{{ route('login.store') }}">
            {{-- 產生隱藏的安全權杖，防止其他網站冒用這張表單。 --}}
            @csrf

            {{-- for 與 input 的 id 相同，點擊文字也能選取輸入框。 --}}
            <label for="shop_code">店家 ID</label>
            {{-- name 是送到控制器的欄位名稱；old 會保留上次輸入值。 --}}
            <input id="shop_code" name="shop_code" value="{{ old('shop_code') }}" required autofocus autocomplete="organization">

            <label for="account">帳號</label>
            {{-- required 讓瀏覽器先阻止空白帳號送出。 --}}
            <input id="account" name="account" value="{{ old('account') }}" required autocomplete="username">

            <label for="password">密碼</label>
            {{-- password 類型會遮住畫面上的密碼，且不保留上次輸入值。 --}}
            <input id="password" name="password" type="password" required autocomplete="current-password">

            {{-- 按下按鈕後送出整張表單。 --}}
            <button type="submit">登入</button>
        </form>
    </main>
</body>
</html>
