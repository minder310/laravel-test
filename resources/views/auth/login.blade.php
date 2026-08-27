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
        /* 讓元素設定的寬高包含內距與邊框，比較容易控制版面。 */
        * { box-sizing: border-box; }
        /* 設定整個頁面的置中方式、底色與預設文字樣式。 */
        body {
            /* 移除瀏覽器預設的頁面外距。 */
            margin: 0;
            /* 頁面最少要和整個瀏覽器視窗一樣高。 */
            min-height: 100vh;
            /* 使用格線排版，方便將登入框水平及垂直置中。 */
            display: grid;
            place-items: center;
            /* 小螢幕時讓登入框與視窗邊緣保留距離。 */
            padding: 24px;
            /* 使用作業系統提供的預設介面字型。 */
            font-family: system-ui, sans-serif;
            /* 設定淺灰色頁面背景。 */
            background: #f4f6f8;
            /* 設定主要文字顏色。 */
            color: #1f2937;
        }
        /* main 是白色的登入卡片。 */
        main {
            /* 最大寬度 420px，小螢幕時則使用全部可用寬度。 */
            width: min(100%, 420px);
            /* 登入卡片內容與邊緣的距離。 */
            padding: 32px;
            /* 將卡片四角變圓。 */
            border-radius: 12px;
            background: white;
            /* 加上淡陰影，讓卡片與背景分離。 */
            box-shadow: 0 12px 30px rgb(0 0 0 / 8%);
        }
        /* 調整登入標題下方距離。 */
        h1 { margin: 0 0 24px; }
        /* 每個欄位標題獨占一行並使用較粗文字。 */
        label { display: block; margin-top: 16px; font-weight: 600; }
        /* 三個輸入欄位共用的外觀。 */
        input {
            width: 100%;
            margin-top: 6px;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font: inherit;
        }
        /* 使用鍵盤或滑鼠選到輸入框時，顯示清楚的藍色外框。 */
        input:focus { outline: 2px solid #93c5fd; border-color: #2563eb; }
        /* 登入按鈕的大小、顏色與文字設定。 */
        button {
            width: 100%;
            margin-top: 24px;
            padding: 12px;
            border: 0;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }
        /* 驗證或登入失敗訊息使用紅色。 */
        .error { margin: 12px 0 0; color: #b91c1c; }
    </style>
</head>
<body>
    {{-- main 包住這個頁面的主要內容。 --}}
    <main>
        <h1>店家登入</h1>

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
