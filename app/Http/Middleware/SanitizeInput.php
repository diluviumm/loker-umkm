<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Buang karakter kontrol (\r dan NUL) dari seluruh input string.
     * Mencegah header/email injection dan path aneh; newline (\n) tetap
     * dipertahankan agar textarea multi-baris tidak rusak.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = str_replace(["\r", "\0"], '', $value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
