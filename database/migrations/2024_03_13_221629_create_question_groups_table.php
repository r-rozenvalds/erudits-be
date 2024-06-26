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
        Schema::create('question_groups', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->integer("disqualify_amount")->default(0)->nullable();
            $table->float("answer_time")->default(1)->nullable();
            $table->integer("points")->default(1)->nullable();
            $table->boolean("is_additional")->default(false)->nullable();
            $table->foreignId("quiz_id")->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_groups');
    }
};
