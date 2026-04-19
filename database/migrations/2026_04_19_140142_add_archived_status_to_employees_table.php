<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change the ENUM to include 'archived'
        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'on_leave', 'archived') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        // Revert: set any 'archived' back to 'inactive' first to avoid data loss
        DB::statement("UPDATE employees SET status = 'inactive' WHERE status = 'archived'");
        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'on_leave') NOT NULL DEFAULT 'active'");
    }
};
