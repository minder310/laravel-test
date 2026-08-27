<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

// 這個控制器集中處理顯示登入頁、驗證登入及登出。
class LoginController extends Controller
{
    // 顯示登入頁面；View 型別表示這個方法會回傳一個畫面。
    public function create(): View
    {
        // auth.login 對應 resources/views/auth/login.blade.php。
        return view('auth.login');
    }

    // 接收登入表單；Request 裝著使用者送來的資料。
    public function store(Request $request): RedirectResponse
    {
        // 先驗證三個欄位，成功後只把通過驗證的資料放進 $credentials。
        $credentials = $request->validate([
            // 店家 ID 必填、必須是文字，而且最多 50 字。
            'shop_code' => ['required', 'string', 'max:50'],
            // 帳號必填、必須是文字，而且最多 100 字。
            'account' => ['required', 'string', 'max:100'],
            // 密碼必填且必須是文字；實際密碼規則可在建立帳號時另外制定。
            'password' => ['required', 'string'],
        ]);

        // 把店家 ID、帳號及來源 IP 組成登入限制的辨識鍵。
        // lower 統一轉小寫，transliterate 則把特殊字元轉成較穩定的形式。
        $throttleKey = Str::transliterate(Str::lower(
            $credentials['shop_code'].'|'.$credentials['account'].'|'.$request->ip()
        ));

        // 同一辨識鍵若已失敗 5 次，就暫時拒絕繼續嘗試。
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            // 使用驗證錯誤的形式把訊息帶回登入頁面。
            throw ValidationException::withMessages([
                'account' => '嘗試次數過多，請稍後再試。',
            ]);
        }

        // 使用登入者輸入的店家代碼查詢 shops 資料表。
        $shopId = Shop::query()
            // 找出 code 等於輸入店家 ID 的那間店。
            ->where('code', $credentials['shop_code'])
            // 只取出店家的資料庫 id；找不到時會得到 null。
            ->value('id');

        // 店家不存在，或「店家＋帳號＋密碼」驗證失敗時，進入錯誤處理。
        if ($shopId === null || ! Auth::attempt([
            // 限定使用者必須屬於剛剛找到的店家。
            'shop_id' => $shopId,
            // 限定登入帳號必須相符。
            'account' => $credentials['account'],
            // Auth 會安全比對輸入密碼與資料庫內的密碼雜湊。
            'password' => $credentials['password'],
        ])) {
            // 記錄一次登入失敗，這次記錄在 60 秒後失效。
            RateLimiter::hit($throttleKey, 60);

            // 統一錯誤訊息，避免洩漏究竟是店家、帳號還是密碼錯誤。
            throw ValidationException::withMessages([
                'account' => '店家 ID、帳號或密碼不正確。',
            ]);
        }

        // 登入成功後清除這組資料之前累積的失敗次數。
        RateLimiter::clear($throttleKey);
        // 重新產生工作階段編號，防止工作階段固定攻擊。
        $request->session()->regenerate();

        // 前往原本想造訪的受保護頁面；沒有紀錄時前往 dashboard。
        return redirect()->intended(route('dashboard'));
    }

    // 處理登出；RedirectResponse 表示最後會轉址到其他頁面。
    public function destroy(Request $request): RedirectResponse
    {
        // 清除 Laravel 記錄的登入身分。
        Auth::logout();
        // 讓目前工作階段失效並清掉其中資料。
        $request->session()->invalidate();
        // 重新產生防止跨站請求攻擊的安全權杖。
        $request->session()->regenerateToken();

        // 登出完成後回到登入頁。
        return redirect()->route('login');
    }
}
