# 📍pinterestmcp📍

## php

```sh
composer require vielhuber/pinterestmcp
```

```php
use vielhuber\pinterestmcp\pinterestmcp;

$pinterest = new pinterestmcp();
$pinterest->userAccountGet();
$pinterest->boardsCreate(body: $body);
$pinterest->boardsList();
$pinterest->boardsGet(board_id: 'your-id');
$pinterest->boardsDelete(board_id: 'your-id');
$pinterest->boardsUpdate(board_id: 'your-id', body: $body);
$pinterest->boardsListPins(board_id: 'your-id');
$pinterest->pinsCreate(body: $body);
$pinterest->pinsList();
$pinterest->pinsGet(pin_id: 'your-id');
$pinterest->pinsUpdate(pin_id: 'your-id', body: $body);
$pinterest->pinsDelete(pin_id: 'your-id');
$pinterest->pinsSave(pin_id: 'your-id', body: $body);
```

set `PINTEREST_ACCESS_TOKEN` via oauth (`$authorization->url()`, `exchangeCode()`, `refreshToken()`). scopes: `user_accounts:read`, `boards:read`, `boards:write`, `pins:read`, `pins:write`. `$body` uses the official request schema. pin editing requires beta access; no home feed.

## mcp

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

stdio is private. http requires https and `MCP_TOKEN`.

[api documentation](https://developers.pinterest.com/docs/)
