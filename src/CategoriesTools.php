<?php

class CategoriesTools
{
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Return MCP tool definitions (JSON Schema) for tools/list response.
     */
    public function getDefinitions(): array
    {
        return [
            [
                'name'        => 'categories_list',
                'description' => 'Get a list of all categories from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => (object)[],
                    'required'   => [],
                ],
            ],
            [
                'name'        => 'categories_get',
                'description' => 'Get a single category by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Category ID',
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'categories_add',
                'description' => 'Add a new category to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name' => [
                            'type'        => 'string',
                            'description' => 'Category name (required)',
                        ],
                        'alias' => [
                            'type'        => 'string',
                            'description' => 'Category alias (slug)',
                        ],
                        'short_description' => [
                            'type'        => 'string',
                            'description' => 'Short description',
                        ],
                        'description' => [
                            'type'        => 'string',
                            'description' => 'Full description',
                        ],
                        'category_parent_id' => [
                            'type'        => 'integer',
                            'description' => 'Parent category ID (0 = top level)',
                        ],
                        'category_publish' => [
                            'type'        => 'integer',
                            'description' => 'Published: 1 or 0 (default 1)',
                            'enum'        => [0, 1],
                        ],
                        'ordering' => [
                            'type'        => 'integer',
                            'description' => 'Sort order',
                        ],
                        'category_image' => [
                            'type'        => 'string',
                            'description' => 'Existing image filename on the server (e.g. "cat1.jpg"). Use this or image.url, not both.',
                        ],
                        'image.url' => [
                            'type'        => 'string',
                            'description' => 'URL to download the image from. The server saves it and stores the filename in category_image. Use this or category_image, not both.',
                        ],
                        'img_alt' => [
                            'type'        => 'string',
                            'description' => 'Image alt text',
                        ],
                        'img_title' => [
                            'type'        => 'string',
                            'description' => 'Image title text',
                        ],
                        'meta_title' => [
                            'type'        => 'string',
                            'description' => 'Meta title',
                        ],
                        'meta_description' => [
                            'type'        => 'string',
                            'description' => 'Meta description',
                        ],
                        'meta_keyword' => [
                            'type'        => 'string',
                            'description' => 'Meta keywords',
                        ],
                        'products_page' => [
                            'type'        => 'integer',
                            'description' => 'Products per page',
                        ],
                        'products_row' => [
                            'type'        => 'integer',
                            'description' => 'Products per row',
                        ],
                        'product_sorting' => [
                            'type'        => 'string',
                            'description' => 'Default sorting field',
                        ],
                        'product_sorting_direction' => [
                            'type'        => 'integer',
                            'description' => 'Sort direction: 1=asc, -1=desc',
                            'enum'        => [1, -1],
                        ],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'categories_edit',
                'description' => 'Edit an existing category in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Category ID (required)',
                        ],
                        'name' => [
                            'type'        => 'string',
                            'description' => 'New name',
                        ],
                        'alias' => [
                            'type'        => 'string',
                            'description' => 'New alias (slug)',
                        ],
                        'short_description' => [
                            'type'        => 'string',
                            'description' => 'New short description',
                        ],
                        'description' => [
                            'type'        => 'string',
                            'description' => 'New full description',
                        ],
                        'category_parent_id' => [
                            'type'        => 'integer',
                            'description' => 'New parent category ID',
                        ],
                        'category_publish' => [
                            'type'        => 'integer',
                            'description' => 'Published: 1 or 0',
                            'enum'        => [0, 1],
                        ],
                        'ordering' => [
                            'type'        => 'integer',
                            'description' => 'Sort order',
                        ],
                        'category_image' => [
                            'type'        => 'string',
                            'description' => 'Existing image filename on the server (e.g. "cat1.jpg"). Use this or image.url, not both.',
                        ],
                        'image.url' => [
                            'type'        => 'string',
                            'description' => 'URL to download the image from. The server saves it and stores the filename in category_image. Use this or category_image, not both.',
                        ],
                        'img_alt' => [
                            'type'        => 'string',
                            'description' => 'Image alt text',
                        ],
                        'img_title' => [
                            'type'        => 'string',
                            'description' => 'Image title text',
                        ],
                        'meta_title' => [
                            'type'        => 'string',
                            'description' => 'Meta title',
                        ],
                        'meta_description' => [
                            'type'        => 'string',
                            'description' => 'Meta description',
                        ],
                        'meta_keyword' => [
                            'type'        => 'string',
                            'description' => 'Meta keywords',
                        ],
                        'products_page' => [
                            'type'        => 'integer',
                            'description' => 'Products per page',
                        ],
                        'products_row' => [
                            'type'        => 'integer',
                            'description' => 'Products per row',
                        ],
                        'product_sorting' => [
                            'type'        => 'string',
                            'description' => 'Default sorting field',
                        ],
                        'product_sorting_direction' => [
                            'type'        => 'integer',
                            'description' => 'Sort direction: 1=asc, -1=desc',
                            'enum'        => [1, -1],
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'categories_delete',
                'description' => 'Delete a category by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Category ID',
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
        ];
    }

    /**
     * Dispatch a tools/call request to the correct method.
     */
    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'categories_list':
                return $this->list();
            case 'categories_get':
                return $this->get((int) $args['id']);
            case 'categories_add':
                return $this->add($args);
            case 'categories_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'categories_delete':
                return $this->delete((int) $args['id']);
            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    // -------------------------------------------------------------------------
    // Private API methods
    // -------------------------------------------------------------------------

    private function list(): array
    {
        $data = $this->client->request('GET', '/api/index.php/v1/shop/categories');
        return $this->formatResponse($data);
    }

    private function get(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/categories/{$id}");
        return $this->formatResponse($data);
    }

    private function add(array $args): array
    {
        $fields = [
            'name', 'alias', 'short_description', 'description',
            'category_parent_id', 'category_publish', 'ordering',
            'category_image', 'image.url', 'img_alt', 'img_title',
            'meta_title', 'meta_description', 'meta_keyword',
            'products_page', 'products_row', 'product_sorting', 'product_sorting_direction',
        ];

        $body = [];
        foreach ($fields as $field) {
            if (isset($args[$field])) {
                $body[$field] = $args[$field];
            }
        }

        $data = $this->client->request('POST', '/api/index.php/v1/shop/categories', $body);
        return $this->formatResponse($data);
    }

    private function edit(int $id, array $args): array
    {
        $fields = [
            'name', 'alias', 'short_description', 'description',
            'category_parent_id', 'category_publish', 'ordering',
            'category_image', 'image.url', 'img_alt', 'img_title',
            'meta_title', 'meta_description', 'meta_keyword',
            'products_page', 'products_row', 'product_sorting', 'product_sorting_direction',
        ];

        $body = [];
        foreach ($fields as $field) {
            if (isset($args[$field])) {
                $body[$field] = $args[$field];
            }
        }

        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/categories/{$id}", $body);
        return $this->formatResponse($data);
    }

    private function delete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/categories/{$id}");
        return [
            [
                'type' => 'text',
                'text' => "Category #{$id} deleted successfully.",
            ],
        ];
    }

    /**
     * Convert JSON:API response to MCP content array.
     */
    private function formatResponse(array $data): array
    {
        if (isset($data['data']) && is_array($data['data']) && isset($data['data'][0])) {
            $lines = [];
            foreach ($data['data'] as $item) {
                $lines[] = $this->formatItem($item);
            }
            return [
                [
                    'type' => 'text',
                    'text' => implode("\n\n", $lines),
                ],
            ];
        }

        if (isset($data['data'])) {
            return [
                [
                    'type' => 'text',
                    'text' => $this->formatItem($data['data']),
                ],
            ];
        }

        return [
            [
                'type' => 'text',
                'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ],
        ];
    }

    private function formatItem(array $item): string
    {
        $id   = $item['id'] ?? '?';
        $attr = $item['attributes'] ?? $item;

        $name       = $attr['name'] ?? '(no name)';
        $alias      = $attr['alias'] ?? '';
        $parent     = $attr['category_parent_id'] ?? 0;
        $published  = isset($attr['category_publish']) ? ($attr['category_publish'] ? 'yes' : 'no') : '?';
        $ordering   = $attr['ordering'] ?? '';
        $image      = $attr['category_image'] ?? '';
        $short_desc = $attr['short_description'] ?? '';
        $meta_title = $attr['meta_title'] ?? '';

        $parts = [
            "ID: {$id}",
            "Name: {$name}",
            "Parent ID: {$parent}",
            "Published: {$published}",
        ];
        if ($alias)      { $parts[] = "Alias: {$alias}"; }
        if ($short_desc) { $parts[] = "Short description: {$short_desc}"; }
        if ($image)      { $parts[] = "Image: {$image}"; }
        if ($meta_title) { $parts[] = "Meta title: {$meta_title}"; }
        if ($ordering !== '') { $parts[] = "Ordering: {$ordering}"; }

        return implode("\n", $parts);
    }
}
