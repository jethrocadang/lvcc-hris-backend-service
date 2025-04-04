<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the employees table.
 *
 * This table stores employment metadata for users,
 * such as position, employment type, and status.
 *
 * Columns:
 * - user_id: References the users table, uniquely identifying an employee.
 * - department_position_id: Links the employee to a specific department and position.
 * - employee_information_id: Links to personal details stored in the employee_informations table.
 * - employee_id: Custom employee identifier.
 * - employee_type: Employment type (full-time, part-time, volunteer).
 * - employment_status: Employment status (regular, probationary).
 * - employment_category: Categorization of employee (teaching, non-teaching).
 * - employee_status: Current status of employment (active, resigned, terminated, contract_ended, on_leave, suspended).
 * - employment_end_date: The date employment ended, if applicable.
 * - latest_position_designation: Last known job title/position.
 * - work_schedule: Placeholder for work schedule information.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method defines the structure of the employees table.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Primary key: Auto-incrementing ID

            // Foreign keys
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade'); // Links to users
            $table->foreignId('department_position_id')->constrained()->onDelete('restrict'); // Links to department positions
            $table->foreignId('employee_information_id')->constrained()->onDelete('cascade'); // Links to employee personal details

            $table->string('employee_id'); // Custom employee ID

            // Employment details
            $table->enum('employee_type', ['full-time', 'part-time', 'volunteer']); // Type of employment
            $table->enum('employment_status', ['regular', 'probationary']); // Employment status
            $table->enum('employment_category', ['teaching', 'non-teaching']); // Employment category
            $table->enum('employee_status', [
                'active',
                'resigned',
                'terminated',
                'contract_ended',
                'on_leave',
                'suspended'
            ]); // Current employee status

            $table->date('employment_end_date')->nullable(); // Date when employment ended (nullable)
            $table->string('latest_position_designation')->nullable(); // Latest position title (optional)
            $table->date('work_schedule')->nullable(); // Placeholder for schedule info (to be refined)

            $table->timestamps(); // Created_at & updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method rolls back the migration by dropping the employees table.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
