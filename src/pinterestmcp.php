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
     * List board sections.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'List board sections. https://developers.pinterest.com/docs/api/v5/#operation/board_sections/list'
    )]
    public function boardSectionsList(
        string $boardId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
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
            path: '/boards/' . $this->client->segment($boardId) . '/sections',
            parameters: $parameters
        );
    }

    /**
     * Write operation. Create board section.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Create board section. https://developers.pinterest.com/docs/api/v5/#operation/board_sections/create'
    )]
    public function boardSectionsCreate(
        string $boardId,
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'string'],
                    'name' => ['type' => 'string', 'maxLength' => 180, 'minLength' => 1]
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
        return $this->client->request(
            method: 'POST',
            path: '/boards/' . $this->client->segment($boardId) . '/sections',
            parameters: $parameters,
            body: $body
        );
    }

    /**
     * Write operation. Delete board section.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Delete board section. https://developers.pinterest.com/docs/api/v5/#operation/board_sections/delete'
    )]
    public function boardSectionsDelete(
        string $boardId,
        string $sectionId,
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
            path: '/boards/' . $this->client->segment($boardId) . '/sections/' . $this->client->segment($sectionId),
            parameters: $parameters
        );
    }

    /**
     * Write operation. Update board section.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Update board section. https://developers.pinterest.com/docs/api/v5/#operation/board_sections/update'
    )]
    public function boardSectionsUpdate(
        string $boardId,
        string $sectionId,
        #[Schema(
            definition: [
                'description' =>
                    'Resource create or update operation model with required body fields (no OptionalProperties).',
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'string'],
                    'name' => ['type' => 'string', 'maxLength' => 180, 'minLength' => 1]
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
        return $this->client->request(
            method: 'PATCH',
            path: '/boards/' . $this->client->segment($boardId) . '/sections/' . $this->client->segment($sectionId),
            parameters: $parameters,
            body: $body
        );
    }

    /**
     * List Pins on board section.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'List Pins on board section. https://developers.pinterest.com/docs/api/v5/#operation/board_sections/list_pins'
    )]
    public function boardSectionsListPins(
        string $boardId,
        string $sectionId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
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
            path: '/boards/' .
                $this->client->segment($boardId) .
                '/sections/' .
                $this->client->segment($sectionId) .
                '/pins',
            parameters: $parameters
        );
    }

    /**
     * List media uploads.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'List media uploads. https://developers.pinterest.com/docs/api/v5/#operation/media/list')]
    public function mediaList(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/media', parameters: $parameters);
    }

    /**
     * Write operation. Register media upload.
     *
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Register media upload. https://developers.pinterest.com/docs/api/v5/#operation/media/create'
    )]
    public function mediaCreate(
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => ['media_type' => ['type' => 'string', 'enum' => ['video']]],
                'required' => ['media_type']
            ]
        )]
        array $body
    ): array {
        return $this->client->request(method: 'POST', path: '/media', body: $body);
    }

    /**
     * Get media upload details.
     *
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get media upload details. https://developers.pinterest.com/docs/api/v5/#operation/media/get'
    )]
    public function mediaGet(string $mediaId): array
    {
        return $this->client->request(method: 'GET', path: '/media/' . $this->client->segment($mediaId));
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
     * Get multiple Pin analytics. Restricted or beta access may be required.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get multiple Pin analytics. Restricted or beta access may be required. https://developers.pinterest.com/docs/api/v5/#operation/multi_pins/analytics'
    )]
    public function multiPinsAnalytics(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'pin_ids' => [
                        'type' => 'array',
                        'items' => ['type' => 'string', 'pattern' => '^\\d+$'],
                        'maxItems' => 100,
                        'minItems' => 1
                    ],
                    'start_date' => ['type' => 'string', 'format' => 'date'],
                    'end_date' => ['type' => 'string', 'format' => 'date'],
                    'app_types' => ['type' => 'string', 'enum' => ['ALL', 'MOBILE', 'TABLET', 'WEB']],
                    'metric_types' => [
                        'type' => 'array',
                        'items' => [
                            'description' => 'Pin metric type for multi-pin analytics',
                            'type' => 'string',
                            'enum' => [
                                'IMPRESSION',
                                'OUTBOUND_CLICK',
                                'PIN_CLICK',
                                'SAVE',
                                'SAVE_RATE',
                                'TOTAL_COMMENTS',
                                'TOTAL_REACTIONS',
                                'USER_FOLLOW',
                                'PROFILE_VISIT',
                                'VIDEO_MRC_VIEW',
                                'VIDEO_10S_VIEW',
                                'QUARTILE_95_PERCENT_VIEW',
                                'VIDEO_V50_WATCH_TIME',
                                'VIDEO_START',
                                'VIDEO_AVG_WATCH_TIME'
                            ]
                        ]
                    ],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']
                ],
                'additionalProperties' => false,
                'required' => ['pin_ids', 'start_date', 'end_date', 'metric_types']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(method: 'GET', path: '/pins/analytics', parameters: $parameters);
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
     * Get Pin analytics.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'Get Pin analytics. https://developers.pinterest.com/docs/api/v5/#operation/pins/analytics')]
    public function pinsAnalytics(
        string $pinId,
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'start_date' => ['type' => 'string', 'format' => 'date'],
                    'end_date' => ['type' => 'string', 'format' => 'date'],
                    'app_types' => ['type' => 'string', 'enum' => ['ALL', 'MOBILE', 'TABLET', 'WEB']],
                    'metric_types' => [
                        'type' => 'array',
                        'items' => [
                            'oneOf' => [
                                [
                                    'description' => 'Standard Pin metric types',
                                    'type' => 'string',
                                    'enum' => [
                                        'IMPRESSION',
                                        'OUTBOUND_CLICK',
                                        'PIN_CLICK',
                                        'SAVE',
                                        'SAVE_RATE',
                                        'TOTAL_COMMENTS',
                                        'TOTAL_REACTIONS',
                                        'USER_FOLLOW',
                                        'PROFILE_VISIT'
                                    ]
                                ],
                                [
                                    'description' => 'Video Pin metric types',
                                    'type' => 'string',
                                    'enum' => [
                                        'IMPRESSION',
                                        'OUTBOUND_CLICK',
                                        'PIN_CLICK',
                                        'SAVE',
                                        'SAVE_RATE',
                                        'VIDEO_MRC_VIEW',
                                        'VIDEO_10S_VIEW',
                                        'QUARTILE_95_PERCENT_VIEW',
                                        'VIDEO_V50_WATCH_TIME',
                                        'VIDEO_START',
                                        'VIDEO_AVG_WATCH_TIME',
                                        'TOTAL_COMMENTS',
                                        'TOTAL_REACTIONS'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'split_field' => ['type' => 'string', 'enum' => ['NO_SPLIT', 'APP_TYPE']],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']
                ],
                'additionalProperties' => false,
                'required' => ['start_date', 'end_date', 'metric_types']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/pins/' . $this->client->segment($pinId) . '/analytics',
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

    /**
     * Search user's boards.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Search user\'s boards. https://developers.pinterest.com/docs/api/v5/#operation/search_user_boards/get'
    )]
    public function searchUserBoardsGet(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'query' => ['type' => 'string'],
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/search/boards', parameters: $parameters);
    }

    /**
     * Search user's Pins.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Search user\'s Pins. https://developers.pinterest.com/docs/api/v5/#operation/search_user_pins/list'
    )]
    public function searchUserPinsList(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'query' => ['type' => 'string'],
                    'bookmark' => ['type' => 'string']
                ],
                'additionalProperties' => false,
                'required' => ['query']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(method: 'GET', path: '/search/pins', parameters: $parameters);
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
     * Get user account analytics.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get user account analytics. https://developers.pinterest.com/docs/api/v5/#operation/user_account/analytics'
    )]
    public function userAccountAnalytics(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'start_date' => ['type' => 'string', 'format' => 'date'],
                    'end_date' => ['type' => 'string', 'format' => 'date'],
                    'from_claimed_content' => ['type' => 'string', 'enum' => ['OTHER', 'CLAIMED', 'BOTH']],
                    'pin_format' => [
                        'type' => 'string',
                        'enum' => [
                            'ALL',
                            'ORGANIC_IMAGE',
                            'ORGANIC_PRODUCT',
                            'ORGANIC_VIDEO',
                            'ADS_STANDARD',
                            'ADS_PRODUCT',
                            'ADS_VIDEO',
                            'ADS_IDEA'
                        ]
                    ],
                    'app_types' => ['type' => 'string', 'enum' => ['ALL', 'MOBILE', 'TABLET', 'WEB']],
                    'content_type' => ['type' => 'string', 'enum' => ['ALL', 'PAID', 'ORGANIC']],
                    'source' => ['type' => 'string', 'enum' => ['ALL', 'YOUR_PINS', 'OTHER_PINS']],
                    'metric_types' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'enum' => [
                                'ENGAGEMENT',
                                'ENGAGEMENT_RATE',
                                'IMPRESSION',
                                'OUTBOUND_CLICK',
                                'OUTBOUND_CLICK_RATE',
                                'PIN_CLICK',
                                'PIN_CLICK_RATE',
                                'SAVE',
                                'SAVE_RATE'
                            ]
                        ]
                    ],
                    'split_field' => [
                        'type' => 'string',
                        'enum' => ['NO_SPLIT', 'APP_TYPE', 'OWNED_CONTENT', 'SOURCE', 'PIN_FORMAT']
                    ],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']
                ],
                'additionalProperties' => false,
                'required' => ['start_date', 'end_date']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account/analytics', parameters: $parameters);
    }

    /**
     * Get user account top pins analytics.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get user account top pins analytics. https://developers.pinterest.com/docs/api/v5/#operation/user_account/analytics/top_pins'
    )]
    public function userAccountAnalyticsTopPins(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'start_date' => ['type' => 'string', 'format' => 'date'],
                    'end_date' => ['type' => 'string', 'format' => 'date'],
                    'sort_by' => [
                        'description' => 'Sort metric for top pins analytics.',
                        'type' => 'string',
                        'enum' => ['ENGAGEMENT', 'SAVE', 'IMPRESSION', 'OUTBOUND_CLICK', 'PIN_CLICK']
                    ],
                    'from_claimed_content' => ['type' => 'string', 'enum' => ['OTHER', 'CLAIMED', 'BOTH']],
                    'pin_format' => [
                        'type' => 'string',
                        'enum' => [
                            'ALL',
                            'ORGANIC_IMAGE',
                            'ORGANIC_PRODUCT',
                            'ORGANIC_VIDEO',
                            'ADS_STANDARD',
                            'ADS_PRODUCT',
                            'ADS_VIDEO',
                            'ADS_IDEA'
                        ]
                    ],
                    'app_types' => ['type' => 'string', 'enum' => ['ALL', 'MOBILE', 'TABLET', 'WEB']],
                    'content_type' => ['type' => 'string', 'enum' => ['ALL', 'PAID', 'ORGANIC']],
                    'source' => ['type' => 'string', 'enum' => ['ALL', 'YOUR_PINS', 'OTHER_PINS']],
                    'metric_types' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'enum' => [
                                'ENGAGEMENT',
                                'ENGAGEMENT_RATE',
                                'IMPRESSION',
                                'OUTBOUND_CLICK',
                                'OUTBOUND_CLICK_RATE',
                                'PIN_CLICK',
                                'PIN_CLICK_RATE',
                                'SAVE',
                                'SAVE_RATE'
                            ]
                        ]
                    ],
                    'num_of_pins' => ['type' => 'integer', 'maximum' => 50, 'minimum' => 1],
                    'created_in_last_n_days' => ['type' => 'number', 'enum' => [30]],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']
                ],
                'additionalProperties' => false,
                'required' => ['start_date', 'end_date', 'sort_by']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account/analytics/top_pins', parameters: $parameters);
    }

    /**
     * Get user account top video pins analytics.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get user account top video pins analytics. https://developers.pinterest.com/docs/api/v5/#operation/user_account/analytics/top_video_pins'
    )]
    public function userAccountAnalyticsTopVideoPins(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'start_date' => ['type' => 'string', 'format' => 'date'],
                    'end_date' => ['type' => 'string', 'format' => 'date'],
                    'sort_by' => [
                        'description' => 'Sort metric for top video pins analytics.',
                        'type' => 'string',
                        'enum' => [
                            'SAVE',
                            'IMPRESSION',
                            'OUTBOUND_CLICK',
                            'VIDEO_MRC_VIEW',
                            'VIDEO_AVG_WATCH_TIME',
                            'VIDEO_V50_WATCH_TIME',
                            'QUARTILE_95_PERCENT_VIEW',
                            'VIDEO_10S_VIEW',
                            'VIDEO_START'
                        ]
                    ],
                    'from_claimed_content' => ['type' => 'string', 'enum' => ['OTHER', 'CLAIMED', 'BOTH']],
                    'pin_format' => [
                        'type' => 'string',
                        'enum' => [
                            'ALL',
                            'ORGANIC_IMAGE',
                            'ORGANIC_PRODUCT',
                            'ORGANIC_VIDEO',
                            'ADS_STANDARD',
                            'ADS_PRODUCT',
                            'ADS_VIDEO',
                            'ADS_IDEA'
                        ]
                    ],
                    'app_types' => ['type' => 'string', 'enum' => ['ALL', 'MOBILE', 'TABLET', 'WEB']],
                    'content_type' => ['type' => 'string', 'enum' => ['ALL', 'PAID', 'ORGANIC']],
                    'source' => ['type' => 'string', 'enum' => ['ALL', 'YOUR_PINS', 'OTHER_PINS']],
                    'metric_types' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'enum' => [
                                'IMPRESSION',
                                'SAVE',
                                'VIDEO_MRC_VIEW',
                                'VIDEO_AVG_WATCH_TIME',
                                'VIDEO_V50_WATCH_TIME',
                                'QUARTILE_95_PERCENT_VIEW',
                                'VIDEO_10S_VIEW',
                                'VIDEO_START',
                                'OUTBOUND_CLICK'
                            ]
                        ]
                    ],
                    'num_of_pins' => ['type' => 'integer', 'maximum' => 50, 'minimum' => 1],
                    'created_in_last_n_days' => ['type' => 'number', 'enum' => [30]],
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$']
                ],
                'additionalProperties' => false,
                'required' => ['start_date', 'end_date', 'sort_by']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(
            method: 'GET',
            path: '/user_account/analytics/top_video_pins',
            parameters: $parameters
        );
    }

    /**
     * List followers.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(description: 'List followers. https://developers.pinterest.com/docs/api/v5/#operation/followers/list')]
    public function followersList(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account/followers', parameters: $parameters);
    }

    /**
     * List following.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'List following. https://developers.pinterest.com/docs/api/v5/#operation/user_following/get'
    )]
    public function userFollowingGet(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'explicit_following' => ['type' => 'boolean'],
                    'feed_type' => [
                        'description' => 'Specifies the type of followees to be kept when filtering them.',
                        'type' => 'string',
                        'enum' => ['ALL', 'RANKED', 'CREATOR_ONLY', 'RANKED_CREATOR_ONLY']
                    ],
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account/following', parameters: $parameters);
    }

    /**
     * List following boards.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'List following boards. https://developers.pinterest.com/docs/api/v5/#operation/boards_user_follows/list'
    )]
    public function boardsUserFollowsList(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'ad_account_id' => ['type' => 'string', 'maxLength' => 18, 'pattern' => '^\\d+$'],
                    'explicit_following' => ['type' => 'boolean'],
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account/following/boards', parameters: $parameters);
    }

    /**
     * Write operation. Follow user. Restricted or beta access may be required.
     *
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Follow user. Restricted or beta access may be required. https://developers.pinterest.com/docs/api/v5/#operation/follow_user/update'
    )]
    public function followUserUpdate(
        string $username,
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => [
                    'auto_follow' => [
                        'description' => '  Whether this request comes as result of auto-follow after clicking on a link.
  Follow links can be used by partners on their site or in emails.
  Only select',
                        'type' => 'boolean'
                    ]
                ]
            ]
        )]
        array $body
    ): array {
        return $this->client->request(
            method: 'POST',
            path: '/user_account/following/' . $this->client->segment($username),
            body: $body
        );
    }

    /**
     * Get user websites.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get user websites. https://developers.pinterest.com/docs/api/v5/#operation/user_websites/get'
    )]
    public function userWebsitesGet(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => [
                    'bookmark' => ['type' => 'string'],
                    'page_size' => ['type' => 'integer', 'maximum' => 250, 'minimum' => 1]
                ],
                'additionalProperties' => false
            ]
        )]
        array $parameters = []
    ): array {
        return $this->client->request(method: 'GET', path: '/user_account/websites', parameters: $parameters);
    }

    /**
     * Write operation. Verify website.
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Verify website. https://developers.pinterest.com/docs/api/v5/#operation/verify_website/update'
    )]
    public function verifyWebsiteUpdate(
        #[Schema(
            definition: [
                'description' => 'Resource create operation model.',
                'type' => 'object',
                'properties' => [
                    'verification_method' => [
                        'description' => 'Method used to verify website ownership.',
                        'type' => 'string',
                        'enum' => ['FILENAME', 'METATAG', 'DNSTXT']
                    ],
                    'website' => ['description' => 'Website with path or domain only', 'type' => 'string']
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
            path: '/user_account/websites',
            parameters: $parameters,
            body: $body
        );
    }

    /**
     * Write operation. Unverify website.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Write operation. Unverify website. https://developers.pinterest.com/docs/api/v5/#operation/unverify_website/delete'
    )]
    public function unverifyWebsiteDelete(
        #[Schema(
            definition: [
                'type' => 'object',
                'properties' => ['website' => ['type' => 'string']],
                'additionalProperties' => false,
                'required' => ['website']
            ]
        )]
        array $parameters
    ): array {
        return $this->client->request(method: 'DELETE', path: '/user_account/websites', parameters: $parameters);
    }

    /**
     * Get user verification code for website claiming.
     *
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     */
    #[McpTool(
        description: 'Get user verification code for website claiming. https://developers.pinterest.com/docs/api/v5/#operation/website_verification/get'
    )]
    public function websiteVerificationGet(
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
            path: '/user_account/websites/verification',
            parameters: $parameters
        );
    }
}
