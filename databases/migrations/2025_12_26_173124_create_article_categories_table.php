<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('name')->comment('分类名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('path')->default('0,')->comment('路径');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_categories');
    }
};
