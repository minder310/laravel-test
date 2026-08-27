<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    // 每個測試都使用乾淨的測試資料庫，測試結束後不影響正式資料。
    use RefreshDatabase;

    // 測試一：確認登入頁可以正常開啟。
    public function test_login_page_is_available(): void
    {
        // 對 /login 發出 GET，預期成功且頁面包含「店家登入」。
        $this->get('/login')->assertOk()->assertSee('店家登入');
    }

    // 測試二：相同帳密在不同店家必須代表不同使用者。
    public function test_same_account_in_different_shops_identifies_different_users(): void
    {
        // 在測試資料庫建立甲店與乙店。
        $shopA = Shop::create(['code' => 'SHOP-A', 'name' => '甲店']);
        $shopB = Shop::create(['code' => 'SHOP-B', 'name' => '乙店']);

        // 建立甲店 admin，並保存到 $userA 供稍後比對登入身分。
        $userA = User::factory()->create([
            'shop_id' => $shopA->id,
            'account' => 'admin',
            'password' => 'secret123',
        ]);
        // 建立乙店 admin，帳號和密碼刻意與甲店相同。
        User::factory()->create([
            'shop_id' => $shopB->id,
            'account' => 'admin',
            'password' => 'secret123',
        ]);

        // 模擬提交甲店登入表單，成功後應轉址到管理頁。
        $this->post('/login', [
            'shop_code' => 'SHOP-A',
            'account' => 'admin',
            'password' => 'secret123',
        ])->assertRedirect('/dashboard');

        // 確認目前登入者正是甲店的 $userA，而非乙店 admin。
        $this->assertAuthenticatedAs($userA);
    }

    // 測試三：不能拿甲店帳號配上乙店 ID 登入。
    public function test_wrong_shop_cannot_login_as_another_shops_user(): void
    {
        // 建立兩間店，但稍後只替甲店建立使用者。
        $shopA = Shop::create(['code' => 'SHOP-A', 'name' => '甲店']);
        Shop::create(['code' => 'SHOP-B', 'name' => '乙店']);

        // 只有甲店擁有 admin 帳號。
        User::factory()->create([
            'shop_id' => $shopA->id,
            'account' => 'admin',
            'password' => 'secret123',
        ]);

        // 故意使用乙店 ID 加上甲店帳密嘗試登入。
        $this->post('/login', [
            'shop_code' => 'SHOP-B',
            'account' => 'admin',
            'password' => 'secret123',
            // 預期 Laravel 把登入錯誤放在 account 欄位的錯誤集合。
        ])->assertSessionHasErrors('account');

        // 最後確認沒有任何使用者被登入。
        $this->assertGuest();
    }
}
