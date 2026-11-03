<?php
declare(strict_types=1);

namespace vielhuber\pinterestmcp;

final class Authorization
{
    /**
     * Keep OAuth application secrets out of the MCP tool surface.
     */
    public function __construct(
        private readonly string $clientId,
        #[\SensitiveParameter] private readonly string $clientSecret,
        private readonly string $redirectUri
    ) {}

    /**
     * Build a consent URL; the caller must persist and verify state on callback.
     *
     * @param list<string> $scopes
     */
    public function url(string $state, array $scopes): string
    {
        if ($state === '') {
            throw PinterestException::failed('Provide an unpredictable OAuth state and verify it on callback.');
        }
        return 'https://www.pinterest.com/oauth/?' .
            http_build_query(
                [
                    'client_id' => $this->clientId,
                    'redirect_uri' => $this->redirectUri,
                    'response_type' => 'code',
                    'scope' => implode(',', $scopes),
                    'state' => $state
                ],
                encoding_type: PHP_QUERY_RFC3986
            );
    }

    /**
     * Obtain credentials for private storage rather than returning them to a tool.
     *
     * @return array<mixed>
     */
    public function exchangeCode(#[\SensitiveParameter] string $code): array
    {
        return new Client('')->grant($this->clientId, $this->clientSecret, [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'continuous_refresh' => 'true'
        ]);
    }

    /**
     * Renew an authorized session without requesting broader scopes.
     *
     * @return array<mixed>
     */
    public function refreshToken(#[\SensitiveParameter] string $refreshToken): array
    {
        return new Client('')->grant($this->clientId, $this->clientSecret, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ]);
    }
}
