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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->text("description");
            $table->foreignId("status_id")->nullable()->constrained("statuses")->nullOnDelete();
            $table->foreignId("created_by")->constrained("users")->cascadeOnDelete();
            $table->foreignId("assigned_to")->constrained("users")->cascadeOnDelete();
            $table->timestamp("finished_at")->nullable();
            $table->enum("priority", ["regular", "important", "urgent", "immediate"])->default("regular");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
