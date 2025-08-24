<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                     // primary key
            $table->string('name');           // User name
            $table->string('email')->unique(); // Email (unique)
            $table->string('phone')->nullable(); // Phone (nullable, make unique if you need)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();          // adds `remember_token` VARCHAR(100) nullable
            $table->timestamps();             // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
