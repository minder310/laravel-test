<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // 建立示範資料時不要觸發模型事件，避免額外副作用。
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 尋找 SHOP-A；已存在就更新名稱，不存在就建立。
        $shopA = Shop::query()->updateOrCreate(
            // 第一個陣列是尋找條件。
            ['code' => 'SHOP-A'],
            // 第二個陣列是要新增或更新的內容。
            ['name' => '甲店'],
        );

        // 用相同方式建立或更新第二間示範店家。
        $shopB = Shop::query()->updateOrCreate(
            ['code' => 'SHOP-B'],
            ['name' => '乙店'],
        );

        // 準備兩位示範管理員；每列依序是店家、姓名、信箱。
        foreach ([
            [$shopA, '甲店管理員', 'shop-a@example.com'],
            [$shopB, '乙店管理員', 'shop-b@example.com'],
        ] as [$shop, $name, $email]) {
            // 逐間店建立 admin；重跑 Seeder 時更新原資料，不會重複新增。
            User::query()->updateOrCreate(
                // 使用「店家＋帳號」尋找，符合多店家登入的身分規則。
                ['shop_id' => $shop->id, 'account' => 'admin'],
                // 兩間店刻意使用相同密碼，以驗證店家仍能區分使用者。
                // User 模型的 hashed 轉型會在寫入前自動雜湊密碼。
                ['name' => $name, 'email' => $email, 'password' => 'password'],
            );
        }
    }
}
