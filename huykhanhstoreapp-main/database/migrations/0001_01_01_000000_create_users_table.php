<?php

// database/migrations/xxxx_xx_xx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique(); // Dùng số điện thoại làm định danh
            $table->string('password');
            
            // Cột status: waiting (chờ), reviewed (đã duyệt). Mặc định là waiting
            $table->enum('status', ['waiting', 'reviewed'])->default('waiting');
            
            // Cột role: user, admin. Mặc định là user
            $table->enum('role', ['user', 'admin'])->default('user');
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
