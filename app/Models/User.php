<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 允許批量寫入店家、姓名、帳號、信箱與密碼。
#[Fillable(['shop_id', 'name', 'account', 'email', 'password'])]
// User 轉成陣列或 JSON 時，不輸出密碼與記住登入用的權杖。
#[Hidden(['password', 'remember_token'])]
// Authenticatable 讓 User 可以使用 Laravel 登入驗證功能。
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    // HasFactory 用於建立測試資料；Notifiable 用於傳送通知。
    use HasFactory, Notifiable;

    // 宣告「一位使用者屬於一間店家」的多對一關係。
    public function shop(): BelongsTo
    {
        // Laravel 會以 users.shop_id 對應 shops.id。
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 讀取 email_verified_at 時，自動轉成日期時間物件。
            'email_verified_at' => 'datetime',
            // 寫入 password 時，自動做不可逆的安全雜湊。
            'password' => 'hashed',
        ];
    }
}
