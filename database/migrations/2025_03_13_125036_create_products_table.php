<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Migration dùng để thao tác với CSDL
    // Trong một file migration bắt buộc phải có 2 hàm UP và DOWN
    // Hàm UP thực hiện các công việc thay đổi hay cập nhật CSDL
    // Hàm DOWN thực hiện các công việc ngược lại với hàm UP

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Xét độ dài và quy định giá trị ko được trùng nhau
            $table->string('ma_san_pham', 20)->unique();
            $table->string('ten_san_pham');
            $table->decimal('gia', 10, 2);
            // nullable cho phép nhập giá trị null
            $table->decimal('gia_khuyen_mai', 10, 2)->nullable();
            $table->unsignedInteger('so_luong'); // Số nguyên dương
            $table->date('ngay_nhap');
            $table->text('mo_ta')->nullable();
            // Xét giá trị mặc định
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
