<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration 用來記錄並執行資料庫結構變更。
return new class extends Migration
{
    // 執行 php artisan migrate 時會執行這個方法。
    public function up(): void
    {
        // 建立 shops（店家）資料表。
        Schema::create('shops', function (Blueprint $table) {
            // 建立自動遞增的 id，作為店家的資料庫主鍵。
            $table->id();
            // 建立店家對外代碼；最多 50 字，且每間店不可重複。
            $table->string('code', 50)->unique();
            // 建立店家顯示名稱欄位。
            $table->string('name');
            // 自動建立 created_at 與 updated_at 兩個時間欄位。
            $table->timestamps();
        });

        // 修改 Laravel 原本已存在的 users（使用者）資料表。
        Schema::table('users', function (Blueprint $table) {
            // 加入 shop_id，連到 shops.id；刪除店家時一併刪除所屬使用者。
            // nullable 是為了容納可能已存在、但尚未指定店家的舊使用者。
            $table->foreignId('shop_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            // 加入登入帳號，最多 100 字；nullable 同樣是為了相容舊資料。
            $table->string('account', 100)->nullable()->after('name');
            // 同一家店不能出現重複帳號，但不同店家可以使用相同帳號。
            $table->unique(['shop_id', 'account']);
        });
    }

    // 執行 php artisan migrate:rollback 時會執行這個方法。
    public function down(): void
    {
        // 把這次加到 users 的限制與欄位依安全順序移除。
        Schema::table('users', function (Blueprint $table) {
            // 先移除「店家＋帳號」的唯一限制。
            $table->dropUnique(['shop_id', 'account']);
            // 移除 shop_id 的外鍵限制，並移除 shop_id 欄位。
            $table->dropConstrainedForeignId('shop_id');
            // 移除 account 欄位。
            $table->dropColumn('account');
        });

        // 最後刪除 shops 資料表；如果不存在則不做任何事。
        Schema::dropIfExists('shops');
    }
};
