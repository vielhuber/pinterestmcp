<?php
declare(strict_types=1);

namespace vielhuber\pinterestmcp;

use vielhuber\simplemcp\Attributes\McpTool;
use vielhuber\simplemcp\Attributes\Schema;

final class pinterestmcp
{
    private readonly Client $client;

    /**
     * Discover tools before credentials exist and authorize only actual API calls.
     */
    public function __construct(#[\SensitiveParameter] ?string $accessToken = null)
    {
        $this->client = new Client(
            $accessToken ?? (getenv('PINTEREST_ACCESS_TOKEN') ?: $_ENV['PINTEREST_ACCESS_TOKEN'] ?? '')
        );
    }
    /**
     * Get user account.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get user account. https://developers.pinterest.com/docs/api/v5/#operation/user_account/get'
    )]
    public function userAccountGet(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account', parameters: $parameters);
    }

    /**
     * Write operation. Create board.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Create board. https://developers.pinterest.com/docs/api/v5/#operation/boards/create'
    )]
    public function boardsCreate(
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => [
                    'description' => ['type' => ['string', 'null']],
                    'is_ads_only' => [
                        'description' => 'If set to `true`, the board will be ad-only and can store ad-only Pins.',
                        'type' => 'boolean'
                    ],
                    'name' => [
                        'description' => '    Name of the board.

    **Note:** If you create an ad-only board by setting `is_ads_only`
    to `true`, the board name automatically becomes "Ad-only Pins"',
                        'type' => 'string'
                    ],
                    'privacy' => [
                        'description' => '    Privacy setting for a board. Learn more about [secret](https://help.pinterest.com/en/article/secret-boards)
    boards and [protected](https://help.pinteres',
                        'type' => 'string',
                        'enum' => ['PUBLIC', 'PROTECTED', 'SECRET']
                    ]
                ],
                'required' => ['name']
            ]
        )]
        array $body,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'POST', path: '/boards', parameters: $parameters, body: $body);
    }

    /**
     * List boards.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'List boards. https://developers.pinterest.com/docs/api/v5/#operation/boards/list')]
    public function boardsList(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'privacy' => [
                        'type' => 'string',
                        'enum' => ['ALL', 'PUBLIC', 'PROTECTED', 'SECRET', 'PUBLIC_AND_SECRET']
                    ],
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/boards', parameters: $parameters);
    }

    /**
     * Get board.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get board. https://developers.pinterest.com/docs/api/v5/#operation/boards/get')]
    public function boardsGet(
        string $boardId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/boards/' . $this->client->segment($boardId),
            parameters: $parameters
        );
    }

    /**
     * Write operation. Delete board.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Delete board. https://developers.pinterest.com/docs/api/v5/#operation/boards/delete'
    )]
    public function boardsDelete(
        string $boardId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'DELETE',
            path: '/boards/' . $this->client->segment($boardId),
            parameters: $parameters
        );
    }

    /**
     * Write operation. Update board.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Update board. https://developers.pinterest.com/docs/api/v5/#operation/boards/update'
    )]
    public function boardsUpdate(
        string $boardId,
        #[Schema(
            definition: [
                'description' => 'Resource create or update operation model.',
                'type' => 'object',
                'properties' => [
                    'description' => ['type' => ['string', 'null']],
                    'name' => [
                        'description' => '    Name of the board.

    **Note:** If you create an ad-only board by setting `is_ads_only`
    to `true`, the board name automatically becomes "Ad-only Pins"',
                        'type' => 'string'
                    ],
                    'privacy' => ['type' => 'string', 'enum' => ['PUBLIC', 'SECRET']]
                ]
            ]
        )]
        array $body,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'PATCH',
            path: '/boards/' . $this->client->segment($boardId),
            parameters: $parameters,
            body: $body
        );
    }

    /**
     * List Pins on board.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'List Pins on board. https://developers.pinterest.com/docs/api/v5/#operation/boards/list_pins'
    )]
    public function boardsListPins(
        string $boardId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'creative_types' => [
                        'type' => 'array',
                        'items' => [
                            'description' => 'Ad creative type enum.
**Note:** SHOP_THE_PIN has been deprecated. Please use COLLECTION instead.',
                            'type' => 'string',
                            'enum' => [
                                'REGULAR',
                                'VIDEO',
                                'SHOPPING',
                                'CAROUSEL',
                                'MAX_VIDEO',
                                'SHOP_THE_PIN',
                                'COLLECTION',
                                'IDEA',
                                'SHOWCASE',
                                'QUIZ',
                                'COLLAGE',
                                'MAX_WIDTH_REGULAR_COLLECTION',
                                'MAX_WIDTH_VIDEO_COLLECTION',
                                'APP'
                            ]
                        ]
                    ],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'pin_metrics' => ['type' => 'boolean'],
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/boards/' . $this->client->segment($boardId) . '/pins',
            parameters: $parameters
        );
    }

    /**
     * Write operation. Create Pin.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Create Pin. https://developers.pinterest.com/docs/api/v5/#operation/pins/create'
    )]
    public function pinsCreate(
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => [
                    'ai_disclosures' => [
                        'description' => 'AI disclosure declarations the creator has made about the Pin.',
                        'type' => 'object',
                        'properties' => [
                            'values' => [
                                'description' =>
                                    'List of AI disclosure declarations the creator has made about this Pin.',
                                'type' => 'array',
                                'items' => [
                                    'description' => 'AI disclosure declaration the creator has made about the Pin.',
                                    'type' => 'string',
                                    'enum' => ['AI_MODIFIED', 'SYNTHETIC_PERFORMER']
                                ]
                            ]
                        ],
                        'required' => ['values']
                    ],
                    'alt_text' => ['type' => ['string', 'null'], 'maxLength' => 500],
                    'board_id' => [
                        'description' => 'The board to which this Pin belongs.',
                        'type' => 'string',
                        'pattern' => '^\\d+$'
                    ],
                    'board_section_id' => [
                        'description' => 'The board section to which this Pin belongs.',
                        'type' => ['string', 'null'],
                        'pattern' => '^\\d+$'
                    ],
                    'description' => ['type' => ['string', 'null'], 'maxLength' => 800],
                    'dominant_color' => [
                        'description' => 'Dominant pin color. Hex number, e.g. `#6E7874`.',
                        'type' => ['string', 'null']
                    ],
                    'link' => ['type' => ['string', 'null'], 'maxLength' => 2048],
                    'media_source' => [
                        'description' =>
                            'Pin media source that can be an image, video, or a mix of both passed in as a request.',
                        'type' => 'object',
                        'oneOf' => [
                            [
                                'description' => 'Image Base64-based media source.',
                                'type' => 'object',
                                'properties' => [
                                    'content_type' => ['type' => 'string', 'enum' => ['image/jpeg', 'image/png']],
                                    'data' => ['type' => 'string', 'pattern' => '^[a-zA-Z0-9+/=]+$'],
                                    'is_standard' => [
                                        'description' =>
                                            'Set the parameter to false to create the new simplified Pin instead of the standard pin. Currently the field is only available to a list of beta users.',
                                        'type' => 'boolean'
                                    ],
                                    'source_type' => [
                                        'description' => 'The source type of the media.',
                                        'type' => 'string',
                                        'enum' => ['image_base64']
                                    ]
                                ],
                                'required' => ['source_type', 'content_type', 'data']
                            ],
                            [
                                'description' => 'Image URL-based media source.',
                                'type' => 'object',
                                'properties' => [
                                    'is_standard' => [
                                        'description' =>
                                            'Set the parameter to false to create the new simplified Pin instead of the standard pin. Currently the field is only available to a list of beta users.',
                                        'type' => 'boolean'
                                    ],
                                    'source_type' => [
                                        'description' => 'The source type of the media.',
                                        'type' => 'string',
                                        'enum' => ['image_url']
                                    ],
                                    'url' => ['type' => 'string']
                                ],
                                'required' => ['source_type', 'url']
                            ],
                            [
                                'description' => 'Video ID-based media source.',
                                'type' => 'object',
                                'properties' => [
                                    'cover_image_content_type' => [
                                        'description' => 'Content type for cover image Base64.',
                                        'type' => 'string',
                                        'enum' => ['image/jpeg', 'image/png']
                                    ],
                                    'cover_image_data' => ['description' => 'Cover image Base64.', 'type' => 'string'],
                                    'cover_image_key_frame_time' => [
                                        'description' =>
                                            'Keyframe timestamp for cover image (seconds). If entered time exceeds video duration, the last frame is used.',
                                        'type' => 'integer',
                                        'minimum' => 0
                                    ],
                                    'cover_image_url' => ['description' => 'Cover image URL.', 'type' => 'string'],
                                    'is_standard' => [
                                        'description' =>
                                            'Set the parameter to false to create the new simplified Pin instead of the standard pin. Currently the field is only available to a list of beta users.',
                                        'type' => 'boolean'
                                    ],
                                    'media_id' => ['type' => 'string', 'pattern' => '^\\d+$'],
                                    'source_type' => ['type' => 'string', 'enum' => ['video_id']]
                                ],
                                'required' => ['media_id', 'source_type']
                            ],
                            [
                                'description' => 'Multiple Base64-based images media source',
                                'type' => 'object',
                                'properties' => [
                                    'index' => ['type' => 'integer', 'minimum' => 0],
                                    'items' => [
                                        'description' => 'Array with image objects.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'content_type' => [
                                                    'type' => 'string',
                                                    'enum' => ['image/jpeg', 'image/png']
                                                ],
                                                'data' => ['type' => 'string', 'pattern' => '^[a-zA-Z0-9+/=]+$'],
                                                'description' => ['type' => 'string'],
                                                'link' => ['type' => 'string'],
                                                'title' => ['type' => 'string']
                                            ],
                                            'required' => ['content_type', 'data']
                                        ],
                                        'maxItems' => 5,
                                        'minItems' => 2
                                    ],
                                    'source_type' => [
                                        'description' => 'The source type of the media.',
                                        'type' => 'string',
                                        'enum' => ['multiple_image_base64']
                                    ]
                                ],
                                'required' => ['source_type', 'items']
                            ],
                            [
                                'description' => 'Multiple URL-based images media source',
                                'type' => 'object',
                                'properties' => [
                                    'index' => ['type' => 'integer', 'minimum' => 0],
                                    'items' => [
                                        'description' => 'Array with image objects.',
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'description' => ['type' => 'string'],
                                                'link' => ['type' => 'string'],
                                                'title' => ['type' => 'string'],
                                                'url' => ['type' => 'string']
                                            ],
                                            'required' => ['url']
                                        ],
                                        'maxItems' => 5,
                                        'minItems' => 2
                                    ],
                                    'source_type' => [
                                        'description' => 'The source type of the media.',
                                        'type' => 'string',
                                        'enum' => ['multiple_image_urls']
                                    ]
                                ],
                                'required' => ['source_type', 'items']
                            ],
                            [
                                'description' =>
                                    'Pin URL-based media source for product pin creation. Currently the field is only available to a list of beta users.',
                                'type' => 'object',
                                'properties' => [
                                    'is_affiliate_link' => [
                                        'description' =>
                                            'This is an affiliate link or sponsored product. The FTC requires disclosure for paid partnerships and affiliate products.',
                                        'type' => 'boolean'
                                    ],
                                    'source_type' => ['type' => 'string', 'enum' => ['pin_url']]
                                ],
                                'required' => ['source_type']
                            ]
                        ]
                    ],
                    'parent_pin_id' => [
                        'description' =>
                            'The source pin id if this pin was saved from another pin. [Learn more](https://help.pinterest.com/article/save-pins-on-pinterest).',
                        'type' => ['string', 'null'],
                        'pattern' => '^\\d+$'
                    ],
                    'sponsor_id' => [
                        'description' =>
                            'The sponsor account id to request paid partnership from.  Currently the field is only available to a list of users in a closed beta.',
                        'type' => ['string', 'null'],
                        'pattern' => '^\\d+$'
                    ],
                    'title' => ['type' => ['string', 'null'], 'maxLength' => 100]
                ]
            ]
        )]
        array $body,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'POST', path: '/pins', parameters: $parameters, body: $body);
    }

    /**
     * List Pins.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'List Pins. https://developers.pinterest.com/docs/api/v5/#operation/pins/list')]
    public function pinsList(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'pin_filter' => [
                        'type' => 'string',
                        'enum' => ['exclude_native', 'exclude_repins', 'has_been_promoted']
                    ],
                    'pin_metrics' => ['type' => 'boolean'],
                    'include_protected_pins' => ['type' => 'boolean'],
                    'pin_type' => ['type' => 'string', 'enum' => ['PRIVATE']],
                    'creative_types' => [
                        'type' => 'array',
                        'items' => [
                            'description' => 'Ad creative type enum.
**Note:** SHOP_THE_PIN has been deprecated. Please use COLLECTION instead.',
                            'type' => 'string',
                            'enum' => [
                                'REGULAR',
                                'VIDEO',
                                'SHOPPING',
                                'CAROUSEL',
                                'MAX_VIDEO',
                                'SHOP_THE_PIN',
                                'COLLECTION',
                                'IDEA',
                                'SHOWCASE',
                                'QUIZ',
                                'COLLAGE',
                                'MAX_WIDTH_REGULAR_COLLECTION',
                                'MAX_WIDTH_VIDEO_COLLECTION',
                                'APP'
                            ]
                        ]
                    ],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'domain' => ['type' => 'string'],
                    'domains' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'maxItems' => 100,
                        'minItems' => 1
                    ],
                    'include_product_tag_obj' => ['type' => 'boolean'],
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/pins', parameters: $parameters);
    }

    /**
     * Get Pin.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get Pin. https://developers.pinterest.com/docs/api/v5/#operation/pins/get')]
    public function pinsGet(
        string $pinId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'pin_metrics' => ['type' => 'boolean']
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/pins/' . $this->client->segment($pinId),
            parameters: $parameters
        );
    }

    /**
     * Write operation. Update Pin. Restricted or beta access may be required.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Update Pin. Restricted or beta access may be required. https://developers.pinterest.com/docs/api/v5/#operation/pins/update'
    )]
    public function pinsUpdate(
        string $pinId,
        #[Schema(
            definition: [
                'description' => 'Resource create or update operation model.',
                'type' => 'object',
                'properties' => [
                    'ai_disclosures' => [
                        'description' => 'AI disclosure declarations the creator has made about the Pin.',
                        'type' => 'object',
                        'properties' => [
                            'values' => [
                                'description' =>
                                    'List of AI disclosure declarations the creator has made about this Pin.',
                                'type' => 'array',
                                'items' => [
                                    'description' => 'AI disclosure declaration the creator has made about the Pin.',
                                    'type' => 'string',
                                    'enum' => ['AI_MODIFIED', 'SYNTHETIC_PERFORMER']
                                ]
                            ]
                        ]
                    ],
                    'alt_text' => ['type' => ['string', 'null'], 'maxLength' => 500],
                    'board_id' => [
                        'description' => 'The board to which this Pin belongs.',
                        'type' => 'string',
                        'pattern' => '^\\d+$'
                    ],
                    'board_section_id' => [
                        'description' => 'The board section to which this Pin belongs.',
                        'type' => ['string', 'null'],
                        'pattern' => '^\\d+$'
                    ],
                    'carousel_slots' => [
                        'description' => 'Carousel Pin slots data.',
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'description' => [
                                    'description' => 'Carousel Pin slot description.',
                                    'type' => 'string'
                                ],
                                'link' => ['description' => 'Carousel Pin slot link.', 'type' => 'string'],
                                'title' => ['description' => 'Carousel Pin slot title.', 'type' => 'string']
                            ]
                        ]
                    ],
                    'description' => ['type' => ['string', 'null'], 'maxLength' => 800],
                    'link' => ['type' => ['string', 'null'], 'maxLength' => 2048],
                    'title' => ['type' => ['string', 'null'], 'maxLength' => 100]
                ]
            ]
        )]
        array $body,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'PATCH',
            path: '/pins/' . $this->client->segment($pinId),
            parameters: $parameters,
            body: $body
        );
    }

    /**
     * Write operation. Delete Pin.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Delete Pin. https://developers.pinterest.com/docs/api/v5/#operation/pins/delete'
    )]
    public function pinsDelete(
        string $pinId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'DELETE',
            path: '/pins/' . $this->client->segment($pinId),
            parameters: $parameters
        );
    }

    /**
     * Write operation. Save Pin.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Save Pin. https://developers.pinterest.com/docs/api/v5/#operation/pins/save'
    )]
    public function pinsSave(
        string $pinId,
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => [
                    'board_id' => [
                        'description' => 'Unique identifier of the board to which the pin will be saved.',
                        'type' => ['string', 'null'],
                        'pattern' => '^\\d+$'
                    ],
                    'board_section_id' => [
                        'description' => 'Unique identifier of the board section to which the pin will be saved.',
                        'type' => ['string', 'null'],
                        'pattern' => '^\\d+$'
                    ]
                ]
            ]
        )]
        array $body,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/pins/' . $this->client->segment($pinId) . '/save',
            parameters: $parameters,
            body: $body
        );
    }
}
