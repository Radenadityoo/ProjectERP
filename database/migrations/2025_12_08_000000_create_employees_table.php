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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('name');
            $table->string('gender', 20)->nullable();
            $table->date('dob')->nullable();
            $table->string('national_id')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('work_email')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('employment_type')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('Active');
            $table->string('profile_photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
