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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('position_applied');
            $table->string('city')->nullable();
            $table->string('country')->default('Bénin');
            $table->text('cover_letter')->nullable();
            $table->string('cv_path');
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->string('education_level')->nullable();
            $table->string('current_company')->nullable();
            $table->decimal('expected_salary', 15, 2)->nullable();
            $table->string('availability')->default('immediate');
            $table->enum('status', ['pending', 'reviewing', 'shortlisted', 'interviewed', 'rejected', 'accepted'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
