<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches_translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code', 20)->index();
            $table->unsignedBigInteger('branches_id')->index();

            // поля, подлежащие переводу
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('history')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('restaurant_type')->nullable();
            $table->string('address')->nullable();

            $table->timestamps();

            $table->unique(['lang_code', 'branches_id']);
            $table->foreign('branches_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches_translations');
    }
};
