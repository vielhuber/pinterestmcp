<?php
declare(strict_types=1);

namespace vielhuber\pinterestmcp;

final class Client
{
    /**
     * Keep account authorization separate from the MCP transport token.
     */
    public function __construct(#[\SensitiveParameter] private readonly string $accessToken) {}

    /**
     * Reject traversal while retaining Pinterest identifiers and usernames.
     */
    public function segment(string $value): string
    {
        if (in_array($value, ['', '.', '..'], strict: true) || strpbrk($value, characters: "/\\\r\n\0") !== false) {
            throw PinterestException::failed('Use a nonempty Pinterest identifier without path separators.');
        }
        return rawurlencode($value);
    }

    /**
     * Forward documented query fields and JSON bodies without retrying writes.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed>|null $body
     * @return array<mixed>
     */
    public function request(string $method, string $path, array $parameters = [], ?array $body = null): array
    {
        if ($this->accessToken === '' || strpbrk($this->accessToken, characters: "\r\n") !== false) {
            throw PinterestException::failed('Configure PINTEREST_ACCESS_TOKEN with an authorized Pinterest token.');
        }
        $parameters = array_filter($parameters, static fn($value) => $value !== null);
        $parameters = array_map(static function ($value): mixed {
            if (is_array($value)) {
                return implode(',', $value);
            }
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }
            return $value;
        }, $parameters);
        $query = http_build_query($parameters, encoding_type: PHP_QUERY_RFC3986);
        return $this->send(
            method: $method,
            path: $path . ($query === '' ? '' : '?' . $query),
            headers: ['Authorization: Bearer ' . $this->accessToken, 'Content-Type: application/json'],
            body: $body === null ? null : json_encode((object) $body, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * Return credentials only to PHP callers, never through an MCP tool.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    public function grant(
        string $clientId,
        #[\SensitiveParameter] string $clientSecret,
        #[\SensitiveParameter] array $parameters
    ): array {
        return $this->send(
            method: 'POST',
            path: '/oauth/token',
            headers: [
                'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),
                'Content-Type: application/x-www-form-urlencoded'
            ],
            body: http_build_query($parameters, encoding_type: PHP_QUERY_RFC3986)
        );
    }

    /**
     * Bound requests to Pinterest and keep uncertain writes visible to callers.
     *
     * @param list<string> $headers
     * @return array<mixed>
     */
    private function send(
        string $method,
        string $path,
        #[\SensitiveParameter] array $headers,
        #[\SensitiveParameter] ?string $body
    ): array {
        $handle = curl_init('https://api.pinterest.com/v5' . $path);
        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_ENCODING => '',
            CURLOPT_PROTOCOLS_STR => 'https',
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'pinterestmcp/0.1 (+https://github.com/vielhuber/pinterestmcp)',
            CURLOPT_HTTPHEADER => array_merge(['Accept: application/json'], $headers)
        ]);
        if ($body !== null) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        }
        $response = curl_exec($handle);
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $uncertain = $method === 'GET' ? '' : ' Read the current state before retrying an uncertain write.';
        if ($response === false || $status < 200 || $status >= 300) {
            throw PinterestException::failed('Pinterest request failed (HTTP ' . $status . ').' . $uncertain, $status);
        }
        if ($response === '') {
            return [];
        }
        try {
            $decoded = json_decode($response, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw PinterestException::failed('Pinterest returned invalid JSON.' . $uncertain);
        }
        if (!is_array($decoded)) {
            throw PinterestException::failed('Pinterest returned an unexpected response.' . $uncertain);
        }
        return $decoded;
    }
}
