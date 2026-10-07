<?php

namespace App\Services;

use App\Models\ApplicationErrorLog;
use App\Support\RequestCorrelation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ApplicationErrorRecorder
{
    public function record(Throwable $exception): void
    {
        if (! config('error_log.enabled', true)) {
            return;
        }

        if ($this->shouldIgnore($exception)) {
            return;
        }

        try {
            $request = request();
            $status = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

            if ($exception instanceof HttpExceptionInterface && $status < 500) {
                return;
            }

            ApplicationErrorLog::create([
                'level' => 'error',
                'exception_class' => $exception::class,
                'message' => Str::limit($exception->getMessage(), 2000),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack_trace' => $this->truncateStack($exception),
                'request_id' => RequestCorrelation::id(),
                'user_id' => Auth::id(),
                'http_method' => $request?->method(),
                'url' => $request?->fullUrl(),
                'route_name' => $request?->route()?->getName(),
                'status_code' => $status,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'context' => $this->buildContext($request, $exception),
                'created_at' => now(),
            ]);
        } catch (Throwable) {
            // Never break the app if error logging fails.
        }
    }

    private function shouldIgnore(Throwable $exception): bool
    {
        foreach (config('error_log.ignore', []) as $class) {
            if ($exception instanceof $class) {
                return true;
            }
        }

        return false;
    }

    private function truncateStack(Throwable $exception): string
    {
        $trace = $exception->getTraceAsString();
        $max = (int) config('error_log.max_stack_bytes', 65536);

        if (strlen($trace) <= $max) {
            return $trace;
        }

        return substr($trace, 0, $max)."\n… [truncated]";
    }

    /** @return array<string, mixed> */
    private function buildContext(?Request $request, Throwable $exception): array
    {
        $context = [
            'previous' => $exception->getPrevious() ? $exception->getPrevious()::class.': '.$exception->getPrevious()->getMessage() : null,
        ];

        if (! $request) {
            return $context;
        }

        $redact = config('error_log.request_keys', []);

        $context['input'] = $this->redact($request->except($redact), $redact);
        $context['query'] = $this->redact($request->query(), $redact);

        return $context;
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $redactKeys
     * @return array<string, mixed>
     */
    private function redact(array $data, array $redactKeys): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $redactKeys, true)) {
                $data[$key] = '[redacted]';
                continue;
            }
            if (is_array($value)) {
                $data[$key] = $this->redact($value, $redactKeys);
            }
        }

        return Arr::undot(Arr::dot($data));
    }
}
