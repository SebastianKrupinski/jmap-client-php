<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Transport;

/**
 * Minimal cookie store used to maintain session state across requests
 */
class CookieJar
{
    /**
     * @var array<string, array{name: string, value: string, domain: string, path: string, secure: bool}>
     */
    private array $cookies = [];

    /**
     * Restore a jar from the array shape produced by {@see toArray()}.
     *
     * @param array<int, array<string, mixed>> $data
     */
    public static function fromArray(array $data): self
    {
        $jar = new self();
        foreach ($data as $cookie) {
            if (!isset($cookie['name'])) {
                continue;
            }
            $jar->store(
                (string)$cookie['name'],
                (string)($cookie['value'] ?? ''),
                (string)($cookie['domain'] ?? ''),
                (string)($cookie['path'] ?? '/'),
                (bool)($cookie['secure'] ?? false),
            );
        }
        return $jar;
    }

    /**
     * Export the jar for persistence via the authentication cookie store callbacks.
     *
     * @return array<int, array{name: string, value: string, domain: string, path: string, secure: bool}>
     */
    public function toArray(): array
    {
        return array_values($this->cookies);
    }

    /**
     * Determine whether the jar currently holds any cookies.
     */
    public function isEmpty(): bool
    {
        return $this->cookies === [];
    }

    /**
     * Build the value for a Cookie request header matching the given target,
     * or an empty string when no cookie applies.
     */
    public function toRequestHeader(string $host, string $path, bool $secure): string
    {
        $host = strtolower($host);
        if ($path === '') {
            $path = '/';
        }

        $pairs = [];
        foreach ($this->cookies as $cookie) {
            if ($cookie['secure'] && !$secure) {
                continue;
            }
            // domain match: exact host or a parent-domain suffix
            $domain = $cookie['domain'];
            if ($domain !== '' && $host !== $domain && !str_ends_with($host, '.' . $domain)) {
                continue;
            }
            // path match: cookie path is a prefix boundary of the request path
            $cookiePath = $cookie['path'];
            if ($cookiePath !== '' && $cookiePath !== '/'
                && $path !== $cookiePath && !str_starts_with($path, rtrim($cookiePath, '/') . '/')) {
                continue;
            }
            $pairs[] = $cookie['name'] . '=' . $cookie['value'];
        }

        return implode('; ', $pairs);
    }

    /**
     * Parse a single Set-Cookie header value and merge it into the jar.
     */
    public function fromSetCookie(string $header, string $host): void
    {
        $parts = array_map('trim', explode(';', $header));
        $pair = array_shift($parts);
        if ($pair === null || !str_contains($pair, '=')) {
            return;
        }
        [$name, $value] = explode('=', $pair, 2);
        $name = trim($name);
        if ($name === '') {
            return;
        }

        $domain = $host;
        $path = '/';
        $secure = false;
        foreach ($parts as $attribute) {
            if ($attribute === '') {
                continue;
            }
            $segments = explode('=', $attribute, 2);
            $key = strtolower(trim($segments[0]));
            $val = isset($segments[1]) ? trim($segments[1]) : '';
            if ($key === 'domain' && $val !== '') {
                $domain = ltrim($val, '.');
            } elseif ($key === 'path' && $val !== '') {
                $path = $val;
            } elseif ($key === 'secure') {
                $secure = true;
            }
        }

        $this->store($name, $value, $domain, $path, $secure);
    }

    /**
     * Insert or replace a stored cookie, keyed by name, domain and path.
     */
    private function store(string $name, string $value, string $domain, string $path, bool $secure): void
    {
        $domain = strtolower($domain);
        $key = $name . ';' . $domain . ';' . $path;
        $this->cookies[$key] = [
            'name' => $name,
            'value' => $value,
            'domain' => $domain,
            'path' => $path,
            'secure' => $secure,
        ];
    }
}
