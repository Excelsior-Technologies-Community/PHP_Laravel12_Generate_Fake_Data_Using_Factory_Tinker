<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class TinkerSandboxService
{
    /**
     * Forbidden keywords and dangerous functions
     */
    protected array $blacklistedKeywords = [
        'exec(',
        'shell_exec(',
        'system(',
        'passthru(',
        'proc_open(',
        'popen(',
        'pcntl_',
        'file_put_contents(',
        'unlink(',
        'rmdir(',
        'chmod(',
        'chown(',
        'touch(',
        'copy(',
        'rename(',
        'curl_exec(',
        'eval(',
        'file_get_contents(\'/etc',
        'file_get_contents(\'c:',
        '$_ENV',
        '$_SERVER',
        'putenv(',
    ];

    /**
     * Execute a user-provided PHP snippet in a controlled sandbox environment
     */
    public function execute(string $code): array
    {
        $code = trim($code);

        if (empty($code)) {
            return [
                'success' => false,
                'error' => 'No code provided for execution.',
                'execution_time_ms' => 0,
                'queries_count' => 0,
                'queries' => [],
            ];
        }

        // Security check for blacklisted tokens
        $lowerCode = strtolower($code);
        foreach ($this->blacklistedKeywords as $forbidden) {
            if (str_contains($lowerCode, strtolower($forbidden))) {
                return [
                    'success' => false,
                    'error' => "Security Violation: Execution of [{$forbidden}] is strictly prohibited in sandbox.",
                    'execution_time_ms' => 0,
                    'queries_count' => 0,
                    'queries' => [],
                ];
            }
        }

        // Auto-append return if it's a simple expression not starting with return or echo
        $executableCode = $this->prepareExecutableCode($code);

        DB::flushQueryLog();
        DB::enableQueryLog();

        $startTime = microtime(true);
        ob_start();

        try {
            // Include namespace aliases in scope
            $closure = function () use ($executableCode) {
                // Available models in scope
                $postClass = Post::class;
                $userClass = User::class;
                $dbClass = DB::class;

                return eval($executableCode);
            };

            $result = $closure();
            $bufferedOutput = ob_get_clean();
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            $queries = DB::getQueryLog();
            $queriesCount = count($queries);

            return [
                'success' => true,
                'result_type' => is_object($result) ? get_class($result) : gettype($result),
                'result' => $this->formatResult($result),
                'raw_output' => $bufferedOutput,
                'execution_time_ms' => $executionTimeMs,
                'queries_count' => $queriesCount,
                'queries' => array_map(fn ($q) => [
                    'query' => $q['query'],
                    'time_ms' => $q['time'] ?? 0,
                ], array_slice($queries, 0, 10)),
            ];

        } catch (Throwable $e) {
            ob_end_clean();
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'execution_time_ms' => $executionTimeMs,
                'queries_count' => count(DB::getQueryLog()),
                'queries' => [],
            ];
        }
    }

    /**
     * Normalizes the user code snippet into an eval-ready string
     */
    protected function prepareExecutableCode(string $code): string
    {
        // Register top-level aliases if not already defined
        if (!class_exists('Post', false)) {
            @class_alias(Post::class, 'Post');
        }
        if (!class_exists('User', false)) {
            @class_alias(User::class, 'User');
        }
        if (!class_exists('DB', false)) {
            @class_alias(DB::class, 'DB');
        }

        // Strip leading <?php if present
        $code = preg_replace('/^<\?(php)?/i', '', $code);
        $code = trim($code);

        // Ensure statement ends with a semicolon
        if (!str_ends_with($code, ';')) {
            $code .= ';';
        }

        // If the code doesn't contain a return statement, and looks like an expression, add return
        if (!preg_match('/^\s*(return|echo|print|if|for|while|foreach)\b/i', $code)) {
            $code = 'return ' . $code;
        }

        return $code;
    }

    /**
     * Formats evaluation result into clean JSON-serializable structure
     */
    protected function formatResult(mixed $result): mixed
    {
        if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
            $result = $result->take(50)->get();
        }

        if ($result instanceof \Illuminate\Support\Collection) {
            return $result->toArray();
        }

        if ($result instanceof \Illuminate\Database\Eloquent\Model) {
            return $result->toArray();
        }

        if (is_resource($result)) {
            return '[Resource]';
        }

        return $result;
    }
}
