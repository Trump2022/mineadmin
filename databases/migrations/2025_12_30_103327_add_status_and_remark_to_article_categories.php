<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('article_categories', static function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('状态:0-停用,1-启用')->after('path');
            $table->string('remark', 255)->default('')->comment('备注')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_categories', static function (Blueprint $table) {
            $table->dropColumn(['status', 'remark']);
        });
    }
};
