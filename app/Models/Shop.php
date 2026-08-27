<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// 只允許使用 create、update 等批量寫入方式修改 code 與 name。
#[Fillable(['code', 'name'])]
// Shop 模型依 Laravel 命名規則自動對應 shops 資料表。
class Shop extends Model
{
    // 宣告「一間店家擁有多位使用者」的一對多關係。
    public function users(): HasMany
    {
        // Laravel 會用 users.shop_id 尋找屬於這間店的使用者。
        return $this->hasMany(User::class);
    }
}
