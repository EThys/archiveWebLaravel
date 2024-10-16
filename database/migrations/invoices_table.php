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
        Schema::create('TInvoices', function (Blueprint $table) {
            $table->bigIncrements("InvoiceId");
            $table->string("InvoiceCode")->nullable();
            $table->string("InvoiceDesc")->nullable();
            $table->string("InvoiceBarCode")->nullable();
            $table->unsignedBigInteger('UserFId');
            $table->unsignedBigInteger('DirectoryFId')->nullable();
            $table->unsignedBigInteger('BranchFId')->nullable();
            $table->string("InvoiceDate")->nullable();
            $table->unsignedBigInteger('InvoiceKeyFId')->nullable();
            $table->unsignedBigInteger('RemoteId')->nullable();
            $table->string("InvoicePath")->nullable();
            $table->string("AndroidVersion")->nullable();
            $table->string("ClientName")->nullable();
            $table->string("ClientPhone")->nullable();
            $table->string("ExpiredDate")->nullable();
            $table->datetime('CreatedAt')->nullable();
            $table->datetime('UpdateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TInvoices');
    }
};
