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
        Schema::create('TSubDirectories', function (Blueprint $table) {
            $table->bigIncrements("SubDirectoryId");
            $table->unsignedBigInteger('DirectoryFId');
            $table->string("SubDirectoryName")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TSubDirectories');
    }
};
