# Laravel 學習專案：AI 接續說明

> 最後更新日期：2026-08-28（Asia/Taipei）

這份文件提供給新的 AI 助手閱讀。開始協助前，請先完整讀完本文件，再查看使用者目前提問涉及的程式碼。

## 零、日期與進度更新規則

這是一份持續更新的交接文件，不是只讀文件。每次 AI 與使用者完成一段實質學習或程式修改後，都應更新本文件。

更新時必須遵守：

1. 文件最上方的「最後更新日期」改成當天日期。
2. 日期一律使用 `YYYY-MM-DD`，並以 `Asia/Taipei` 時區為準。
3. 在最下方的「學習進度日誌」新增一筆當天紀錄；不要刪除以前的紀錄。
4. 同一天有多次進度，可以在同一日期下增加項目。
5. 每筆紀錄至少寫明「今天理解／完成什麼」與「下一步從哪裡開始」。
6. 若有修改程式，列出修改的檔案與測試結果。
7. 若只有討論、沒有修改程式，也要明確寫上「本次未修改程式」。
8. 不要在文件中記錄 `.env` 密碼、應用程式金鑰、權杖或其他秘密。
9. 更新文件前應先閱讀既有最後一筆日誌，避免重複教學或跳過尚未理解的內容。

日誌範本：

```markdown
### YYYY-MM-DD

- 今天理解／完成：
- 修改檔案：本次未修改程式（或列出檔案）
- 驗證結果：未執行測試（或填寫測試結果）
- 目前停在：
- 下一步：
```

## 一、使用者的學習目標

使用者正在從基礎開始學習 Laravel，希望理解每一層如何合作，而不只是取得完成的程式碼。

使用者目前已大致理解：

- Ubuntu 是作業系統，不是 Apache。
- Docker 負責管理容器。
- 此專案使用 Nginx 接收網站請求。
- PHP-FPM 執行 Laravel。
- MySQL 儲存資料。
- Apache 是 Ubuntu 中另一套獨立網站伺服器，不參與此 Docker Laravel 專案。
- 此專案的 Laravel 網址是 `http://localhost:8080`。
- `.env` 不應提交到 Git，`.env.example` 才是可提交的設定範本。
- Laravel 網址會先經過 Route，再進入 Controller 或 View。

## 二、與使用者互動的必要方式

請使用繁體中文，並遵守以下教學方式：

1. 一次只教一個小步驟，不要一次完成整套功能。
2. 先解釋這一步的目的，以及會影響哪個檔案。
3. 提供少量、可親手輸入的程式碼。
4. 優先讓使用者自己新增或修改檔案。
5. 寫完後請使用者回報結果或貼出程式碼，再協助檢查。
6. 沒有得到明確授權時，不要直接替使用者實作下一個功能。
7. 如果使用者明確要求 AI 直接修改，才可動手修改；修改後仍要逐檔解釋。
8. 解釋語法時要拆開說明，例如把路由拆成 HTTP 方法、網址、控制器、方法與路由名稱。
9. 不要假設使用者已理解 MVC、HTTP、Session、外鍵或中介層；第一次遇到時使用具體例子說明。
10. 避免一次丟出太多新名詞。若內容很多，先指出今天只需理解哪一部分。

理想的互動節奏：

```text
AI 解釋一小步
→ 使用者親手操作
→ AI 檢查結果
→ 確認理解
→ 才進入下一步
```

## 三、目前專案環境

專案路徑：

```text
/home/ser20251216/projects/My-Test
```

執行架構：

```text
瀏覽器
→ http://localhost:8080
→ Docker Nginx 容器
→ Docker PHP-FPM 容器
→ Laravel
→ Docker MySQL 容器
```

Docker Compose 服務：

- `nginx`：主機的 8080 對應容器的 80。
- `php`：PHP 8.3 FPM，執行 Laravel。
- `mysql`：MySQL 8.0。

此專案的 PHP、Composer 和 Artisan 指令，優先透過 PHP 容器執行：

```bash
docker compose exec php php artisan ...
docker compose exec php composer ...
```

## 四、目前已存在的頁面

```text
GET /          Laravel 原始歡迎頁，仍然保留
GET /hello     使用者先前建立的練習頁面
GET /login     多店家登入頁面
POST /login    處理登入
GET /dashboard 登入後頁面
POST /logout   處理登出
```

## 五、已完成的多店家登入需求

登入時需要輸入：

1. 店家 ID
2. 帳號
3. 密碼

身分判斷規則：

```text
店家＋帳號＋密碼
```

不同店家可以使用相同帳號：

```text
SHOP-A + admin  合法
SHOP-B + admin  合法
```

同一家店不能有兩個相同帳號，資料庫使用以下複合唯一限制：

```php
$table->unique(['shop_id', 'account']);
```

## 六、這次已修改或新增的檔案

### `routes/web.php`

定義首頁、Hello 頁、登入、管理頁與登出路由。檔案中已有繁體中文教學註解。

### `app/Http/Controllers/Auth/LoginController.php`

包含：

- `create()`：顯示登入頁。
- `store()`：驗證三個欄位、限制登入嘗試、尋找店家並執行登入。
- `destroy()`：登出並清除 Session。

檔案中已有密集的繁體中文教學註解。

### `resources/views/auth/login.blade.php`

三欄登入表單，包含 CSRF、錯誤訊息、保留舊輸入值及基本 CSS。HTML、Blade 和 CSS 都已有中文註解。

### `resources/views/dashboard.blade.php`

登入成功後顯示目前使用者姓名，並提供登出表單。

### `database/migrations/2026_08_28_000000_create_shops_and_add_shop_login_to_users.php`

建立 `shops` 資料表，並在 `users` 加入：

- `shop_id`
- `account`
- `shop_id + account` 複合唯一限制

目前 `shop_id` 與 `account` 是 nullable，用來相容可能存在的舊使用者。若未來確認所有使用者資料都完成轉換，可以另建 Migration 改成不可為空。

### `app/Models/Shop.php`

店家 Model，包含 `users()` 一對多關係。

### `app/Models/User.php`

加入 `shop_id`、`account` 可寫入欄位，以及 `shop()` 所屬店家關係。密碼使用 Laravel 的 `hashed` 轉型。

### `database/seeders/DatabaseSeeder.php`

建立兩間本機示範店家及相同帳密的管理員：

| 店家 ID | 帳號 | 密碼 | 使用者 |
|---|---|---|---|
| `SHOP-A` | `admin` | `password` | 甲店管理員 |
| `SHOP-B` | `admin` | `password` | 乙店管理員 |

這些只供本機學習，不可作為正式環境密碼。

### `tests/Feature/LoginTest.php`

測試：

- 登入頁可以開啟。
- 不同店家使用相同帳密時，仍會登入正確使用者。
- 不能拿甲店帳號搭配乙店 ID 登入。

### `docker-compose.yml`

已移除 PHP 服務中固定的 `APP_ENV=local`，避免它蓋過測試需要的 `APP_ENV=testing`。

## 七、目前驗證狀態

最近一次執行：

```bash
docker compose exec php php artisan test
```

結果：

```text
5 項測試通過
12 個驗證通過
```

## 八、目前教學進度與接續位置

使用者目前正在閱讀 `routes/web.php`，並剛學到以下程式：

```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});
```

已向使用者解釋：

- `guest` 檢查的是目前是否尚未登入，不是檢查表單有沒有資料。
- `[LoginController::class, 'create']` 表示呼叫 `LoginController` 的 `create()` 方法。
- `name('login')` 是替路由取代號，不是網址或方法名稱。
- `GET /login` 用來取得登入表單。
- `POST /login` 用來提交並處理登入資料。

### 建議下一步

先不要繼續增加新功能。下一次應從 `LoginController::create()` 與登入 Blade 表單的連接開始，讓使用者自己沿著以下流程查找：

```text
GET /login
→ LoginController::create()
→ view('auth.login')
→ resources/views/auth/login.blade.php
```

可以請使用者親自在三個檔案中找到這幾段，並用自己的話描述流程。確認理解後，再進入 `POST /login → store()`，而且一次只解釋 `request->validate()`，不要立即講完整登入控制器。

## 九、給新 AI 的開場建議

可以先說：

> 我已閱讀專案的接續文件。你目前已理解 GET 與 POST 登入路由的基本差異。接下來我們先只追蹤 GET `/login`：從路由找到 Controller，再找到 Blade 頁面；這一步不修改任何程式。請先打開 `routes/web.php` 和 `LoginController.php`，我們一起找出對應位置。

如果使用者改問其他問題，優先回答當前問題，不必強迫按照原定課程進度。

## 十、學習進度日誌

### 2026-08-28

- 今天理解／完成：釐清 `guest` 是判斷目前是否尚未登入，不是判斷表單有沒有資料；理解 `[LoginController::class, 'create']` 是指定控制器與方法；理解路由名稱；理解 `GET /login` 顯示表單、`POST /login` 處理表單。
- 修改檔案：替多店家登入相關 Migration、Model、Controller、Route、Blade、Seeder、Test 與 Docker Compose 加入繁體中文教學註解；建立並更新本交接文件。
- 驗證結果：Laravel 測試共 5 項通過、12 個驗證通過；`git diff --check` 通過。
- 目前停在：已看過登入路由的基本語法，但尚未逐步追蹤 `GET /login` 如何從 Route 走到 Controller 和 Blade。
- 下一步：只追蹤 `GET /login → LoginController::create() → view('auth.login') → resources/views/auth/login.blade.php`，由使用者親自在檔案中找出對應程式並用自己的話說明；先不修改程式。
