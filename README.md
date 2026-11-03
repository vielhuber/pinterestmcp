# 📍pinterestmcp📍

your [pinterest](https://developers.pinterest.com/docs/), ready for php and ai.

## functions

```text
boardsCreate(), boardsList(), boardsGet(), boardsDelete(), boardsUpdate(), boardsListPins(),
boardSectionsList(), boardSectionsCreate(), boardSectionsDelete(), boardSectionsUpdate(),
boardSectionsListPins(), mediaList(), mediaCreate(), mediaGet(), pinsCreate(), pinsList(),
multiPinsAnalytics(), pinsGet(), pinsUpdate(), pinsDelete(), pinsAnalytics(), pinsSave(),
searchUserBoardsGet(), searchUserPinsList(), userAccountGet(), userAccountAnalytics(),
userAccountAnalyticsTopPins(), userAccountAnalyticsTopVideoPins(), followersList(), userFollowingGet(),
boardsUserFollowsList(), followUserUpdate(), userWebsitesGet(), verifyWebsiteUpdate(),
unverifyWebsiteDelete(), websiteVerificationGet()
```

## installation

```sh
composer require vielhuber/pinterestmcp
```

## php

```php
use vielhuber\pinterestmcp\pinterestmcp;

$pinterest = new pinterestmcp();
$pinterest->pinsList();
$pinterest->boardsList();
$pinterest->pinsCreate(body: [
    'board_id' => 'your-board-id',
    'media_source' => ['source_type' => 'image_url', 'url' => 'https://example.com/image.jpg'],
    'title' => 'example title'
]);
```

set `PINTEREST_ACCESS_TOKEN` after authorizing your developer app with the required `user_accounts:read`, `user_accounts:write`, `boards:read`, `boards:write`, `pins:read` and `pins:write` scopes. [access tiers](https://developers.pinterest.com/docs/key-concepts/access-tiers/) and beta restrictions still apply: trial-created content is only visible to its creator; pin editing may require beta access.

covers account, pins, boards, sections, organic analytics, search and media registration. no ads, catalogs, shopping, product tags or partner search. media tools register uploads; transfer the bytes to the returned upload target separately. there is no complete social activity timeline or home feed. `bookmark` remains the api pagination cursor.

for oauth, use `Authorization(clientId: ..., clientSecret: ..., redirectUri: ...)`, then `url(state: ..., scopes: [...])`, `exchangeCode(...)` and `refreshToken(...)`. persist and verify state yourself. store returned credentials privately; these helpers are php-only, not mcp tools.

## mcp

the same api functions are tools (snake_case names). configure your client:

```json
{
    "mcpServers": {
        "pinterest": {
            "command": "php",
            "args": ["/absolute/path/to/vendor/bin/mcp-server.php"],
            "env": {
                "PINTEREST_ACCESS_TOKEN": "your-access-token"
            }
        }
    }
}
```

stdio needs no mcp token. for http, set a separate `MCP_TOKEN` in `.env`, require https and deny direct access to `.env`. never expose the server without authentication.

api failures are surfaced without automatic retries. after an uncertain write, read the current state before repeating it. credentials are never included in error messages.

## development

```sh
composer install
npm install
composer generate
composer lint
npm run format:check
```

endpoint metadata lives in `resources/endpoints.json`; `composer generate` rebuilds the php tools from it.
