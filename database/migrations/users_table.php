<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('TUsers', function (Blueprint $table) {
            $table->bigIncrements('UserId');
            $table->unsignedBigInteger('BranchFId')->nullable();
            $table->string('UserName');
            $table->string('Password');
            $table->boolean('IsAdmin')->default(false);
            $table->string('SerialNumber');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TUsers');
    }
};
