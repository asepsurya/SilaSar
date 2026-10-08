<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('browser_notified')->default(false)->after('is_read');
            $table->timestamp('notified_at')->nullable()->after('browser_notified');
            $table->index(['user_id', 'browser_notified', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'browser_notified', 'type']);
            $table->dropColumn(['browser_notified', 'notified_at']);
        });
    }
};
