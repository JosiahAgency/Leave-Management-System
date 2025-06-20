<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->string('userID');
            $table->integer('leaveTypeID');
            $table->string('supervisor');
            $table->date('startDate');
            $table->date('endDate');
            $table->text('reason')->nullable();
            $table->string('supportingDocument')->nullable();
            $table->enum('status', ['Pending', 'Granted', 'Denied'])->default('Pending');
            $table->enum('hod_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('hr_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('hod_comment')->nullable();
            $table->text('hr_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
