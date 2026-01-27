<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Dropping admin_id from labs...\n";
if (Schema::hasColumn('labs', 'admin_id')) {
    Schema::table('labs', function (Blueprint $table) {
        try {
            $table->dropForeign(['admin_id']);
        } catch (\Throwable $e) {
            echo "Foreign key check failed (ignoring): " . $e->getMessage() . "\n";
        }

        try {
            $table->dropColumn('admin_id');
        } catch (\Throwable $e) {
            echo "Column drop failed: " . $e->getMessage() . "\n";
        }
    });
    echo "Dropped admin_id.\n";
} else {
    echo "Column admin_id not found.\n";
}
