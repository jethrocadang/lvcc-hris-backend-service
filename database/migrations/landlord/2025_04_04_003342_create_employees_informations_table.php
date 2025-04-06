<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the employee_informations table.
 *
 * This table stores personal and employment-related information
 * of employees, including contact details, identification numbers,
 * and other relevant data.
 *
 * Columns:
 * - first_name: Employee's first name.
 * - middle_name: Employee's middle name (nullable).
 * - last_name: Employee's last name.
 * - date_hired: Date when the employee was hired.
 * - contact_number: Employee's phone number.
 * - current_address: Employee's current residence address.
 * - permanent_address: Employee's permanent residence address.
 * - birth_date: Employee's birthdate.
 * - baptism_date: Date of baptism (nullable).
 * - religion: Employee's religion (nullable).
 * - gender: Gender identity (male, female).
 * - marital_status: Employee's marital status.
 * - educational_attainment: Highest level of education attained (nullable).
 * - license: Professional license or certification (nullable).
 * - tin_number: Tax Identification Number (nullable).
 * - pagibig_number: Pag-IBIG Fund number (nullable).
 * - sss_number: Social Security System (SSS) number (nullable).
 * - philhealth_number: PhilHealth number (nullable).
 * - work_email: Official work email (unique).
 * - personal_email: Personal email address (nullable, unique).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method defines the structure of the employee_informations table.
     */
    public function up(): void
    {
        Schema::create('employee_informations', function (Blueprint $table) {
            $table->id(); // Primary key: Auto-incrementing ID

            // Personal details
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_hired');
            $table->string('contact_number');
            $table->string('current_address');
            $table->string('permanent_address');
            $table->date('birth_date');
            $table->date('baptism_date')->nullable();
            $table->string('religion')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital_status', ['married', 'widowed', 'separated', 'single']);

            // Employment and identification details
            $table->string('educational_attainment')->nullable();
            $table->string('license')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('philhealth_number')->nullable();

            // Contact details
            $table->string('work_email')->unique();
            $table->string('personal_email')->nullable()->unique();

            $table->timestamps(); // Created_at & updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method rolls back the migration by dropping the employee_informations table.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_informations');
    }
};
