<?php
/**
 * Lightweight, database-free smoke tests for Quick-Ask.
 *
 * Run from the repository root with: php tests/run.php
 * These checks verify important source-level contracts; they do not replace
 * integration tests against a configured MySQL database.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$checks = 0;
$failures = [];

function check(bool $condition, string $message): void
{
    global $checks, $failures;
    $checks++;

    if (!$condition) {
        $failures[] = $message;
        fwrite(STDERR, "FAIL: {$message}\n");
        return;
    }

    fwrite(STDOUT, "PASS: {$message}\n");
}

function source(string $relativePath): string
{
    global $root;
    $path = $root . DIRECTORY_SEPARATOR . $relativePath;

    if (!is_file($path)) {
        check(false, "required file exists: {$relativePath}");
        return '';
    }

    $contents = file_get_contents($path);
    check($contents !== false, "required file is readable: {$relativePath}");

    return $contents === false ? '' : $contents;
}

$index = source('index.php');
$request = source('server/request.php');
$database = source('server/db.php');
$questions = source('main/question.php');
$detail = source('main/question-detail.php');

// Entry-point and authentication routing.
check(
    str_contains($index, 'session_start()'),
    'entry point starts a session'
);
check(
    str_contains($index, "isset(\$_SESSION['user']['username'])")
        && str_contains($index, 'login=true&redirect=ask-question'),
    'asking a question redirects guests to login'
);

// Database access and user-submitted values.
check(
    str_contains($database, 'new PDO(')
        && str_contains($database, 'PDO::ATTR_ERRMODE'),
    'database connection uses PDO exception mode'
);
check(
    preg_match('/prepare\s*\(/i', $request) === 1
        && str_contains($request, '$stmt->execute(['),
    'request handlers use prepared statements with bound values'
);
check(
    str_contains($questions, 'WHERE q.title LIKE ?')
        && str_contains($questions, '$stmt->execute([$search])'),
    'question search binds the search term instead of interpolating SQL'
);

// Output escaping for user-controlled content.
check(
    str_contains($questions, "htmlspecialchars(\$q['title'])")
        && str_contains($questions, "htmlspecialchars(\$q['username'])")
        && str_contains($questions, 'htmlspecialchars($tag)'),
    'question list escapes titles, usernames, and tags'
);
check(
    str_contains($detail, 'htmlspecialchars('),
    'question detail escapes displayed user content'
);

fwrite(STDOUT, "\n{$checks} checks run; " . count($failures) . " failed.\n");

exit($failures === [] ? 0 : 1);
