<!DOCTYPE html>
{{-- 這是只有登入使用者才能開啟的簡單管理頁。 --}}
<html lang="zh-TW">
<head>
    {{-- 設定中文字元編碼。 --}}
    <meta charset="UTF-8">
    {{-- 讓頁面在手機上正確顯示。 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- 瀏覽器分頁標題。 --}}
    <title>管理頁面</title>
</head>
<body>
    <h1>登入成功</h1>
    {{-- auth()->user() 取得目前登入者，name 是使用者姓名欄位。 --}}
    <p>你好，{{ auth()->user()->name }}</p>

    {{-- 登出使用 POST 送到名為 logout 的路由。 --}}
    <form method="POST" action="{{ route('logout') }}">
        {{-- 所有會改變伺服器狀態的表單都應加入安全權杖。 --}}
        @csrf
        {{-- 按下後送出登出表單。 --}}
        <button type="submit">登出</button>
    </form>
</body>
</html>
