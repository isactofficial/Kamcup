<?php
// Simple test script to verify comment functionality
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

echo "=== Comment System Debug ===\n\n";

// Check routes
echo "1. Comment Routes:\n";
shell_exec('php artisan route:list 2>&1 | select-string -pattern "comments"');

// Check controller exists
echo "\n2. Comment Controller:\n";
$controllerPath = __DIR__ . '/app/Http/Controllers/CommentController.php';
echo "Exists: " . (file_exists($controllerPath) ? "YES" : "NO") . "\n";

// Check model
echo "\n3. Comment Model:\n";
$modelPath = __DIR__ . '/app/Models/Comment.php';
echo "Exists: " . (file_exists($modelPath) ? "YES" : "NO") . "\n";

//  Check middleware
echo "\n4. Auth Middleware:\n";
$authPath = __DIR__ . '/app/Http/Middleware';
echo "Dir exists: " . (is_dir($authPath) ? "YES" : "NO") . "\n";

// Check if request class has expectsJson
echo "\n5. Request Methods:\n";
$reflectionClass = new ReflectionClass(\Illuminate\Http\Request::class);
echo "expectsJson method: " . ($reflectionClass->hasMethod('expectsJson') ? "YES" : "NO") . "\n";
echo "header method: " . ($reflectionClass->hasMethod('header') ? "YES" : "NO") . "\n";

echo "\n=== End Debug ===\n";
?>
