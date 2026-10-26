<?php
/**
 * Lightweight regression smoke tests for Quick-Ask.
 *
 * Run from the repository root with:
 *   php tests/smoke.php
 *
 * These tests intentionally do not require a configured MySQL database. They
 * check key application files and security/flow invariants in the source.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$passed = 0;
$failed = 0;

function check(bool $condition, string $message): void
{
    global $passed, $failed;

    if ($condition) {
        ++$passed;
        echo "PASS: {$message}\n";
        return;
    }

    ++$failed;
    echo "FAIL: {$message}\n";
}

function readSource(string $root, string $relativePath): string
{
    $path = $root . DIRECTORY_SEPARATOR . $relativePath;

    if (!is_file($path)) {
        return '';
    }

    $contents = file_get_contents($path);
    return $contents === false ? '' : $contents;
}

$requiredFiles = [
    'index.php',
    'server/db.php',
    'server/request.php',
    'main/question.php',
    'main/question-detail.php',
    'user/login.php',
    'user/signup.php',
];

foreach ($requiredFiles as $relativePath) {
    check(
        is_file($root . DIRECTORY_SEPARATOR . $relativePath),
        "required application file exists: {$relativePath}"
    );
}

$db = readSource($root, 'server/db.php');
$request = readSource($root, 'server/request.php');
$index = readSource($root, 'index.php');
$questions = readSource($root, 'main/question.php');
$questionDetail = readSource($root, 'main/question-detail.php');

check(
    strpos($db, 'mysql:host=$host;dbname=$dbname;charset=utf8mb4') !== false,
    'database connection uses utf8mb4'
);
check(
    strpos($db, 'PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION') !== false,
    'database errors are configured to throw exceptions'
);
check(
    strpos($request, 'prepare("SELECT * FROM users WHERE email = ?")') !== false,
    'authentication email lookups use a prepared statement'
);
check(
    strpos($request, '->execute([$email])') !== false,
    'authentication email values are bound through execute parameters'
);
check(
    strpos($request, 'Insert into `users` (username,email,password) values(?,?,?)') !== false,
    'registration inserts use placeholders'
);
check(
    strpos($request, 'Insert into `question` (title') !== false
        && strpos($request, '$_SESSION[\'user\']["user_id"]') !== false,
    'question creation associates the question with the session user'
);
check(
    strpos($index, 'if (!isset($_SESSION[\'user\'][\'username\']))') !== false
        && strpos($index, 'login=true&redirect=ask-question') !== false,
    'guests are redirected to login before accessing question creation'
);
check(
    strpos($questions, 'htmlspecialchars(') !== false,
    'question listing escapes output before rendering user content'
);
check(
    strpos($questionDetail, 'htmlspecialchars(') !== false,
    'question detail view escapes output before rendering user content'
);

// Run PHP's own syntax checker on application PHP files when the CLI is
// available. This avoids loading application code or connecting to MySQL.
if (function_exists('exec')) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getPathname();
        if (strpos($path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) !== false) {
            continue;
        }

        $output = [];
        $status = 0;
        exec(
            escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1',
            $output,
            $status
        );

        check($status === 0, 'PHP syntax is valid: ' . substr($path, strlen($root) + 1));
    }
} else {
    echo "SKIP: PHP syntax checks (exec is disabled in this environment)\n";
}

echo "\nResults: {$passed} passed, {$failed} failed.\n";
exit($failed === 0 ? 0 : 1);
