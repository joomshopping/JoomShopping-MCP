<?php

class ManufacturersTools
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
                'name'        => 'manufacturers_list',
                'description' => 'Get a list of all manufacturers from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => (object)[],
                    'required'   => [],
                ],
            ],
            [
                'name'        => 'manufacturers_get',
                'description' => 'Get a single manufacturer by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Manufacturer ID',
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'manufacturers_add',
                'description' => 'Add a new manufacturer to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer name (required)',
                        ],
                        'short_description' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer short description',
                        ],
                        'description' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer description',
                        ],
                        'alias' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer alias (slug)',
                        ],
                        'manufacturer_url' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer website URL',
                        ],
                        'manufacturer_logo' => [
                            'type'        => 'string',
                            'description' => 'Existing logo filename on the server. Use this or image.url, not both.',
                        ],
                        'image.url' => [
                            'type'        => 'string',
                            'description' => 'URL to download the logo from. The server saves it and stores the filename in manufacturer_logo. Use this or manufacturer_logo, not both.',
                        ],
                        'manufacturer_publish' => [
                            'type'        => 'integer',
                            'description' => 'Published: 1 or 0 (default 1)',
                            'enum'        => [0, 1],
                        ],
                        'ordering' => [
                            'type'        => 'integer',
                            'description' => 'Sort order',
                        ],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'manufacturers_edit',
                'description' => 'Edit an existing manufacturer in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Manufacturer ID (required)',
                        ],
                        'name' => [
                            'type'        => 'string',
                            'description' => 'New name',
                        ],
                        'short_description' => [
                            'type'        => 'string',
                            'description' => 'New short description',
                        ],
                        'description' => [
                            'type'        => 'string',
                            'description' => 'New description',
                        ],
                        'alias' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer alias (slug)',
                        ],
                        'manufacturer_url' => [
                            'type'        => 'string',
                            'description' => 'New website URL',
                        ],
                        'manufacturer_logo' => [
                            'type'        => 'string',
                            'description' => 'Existing logo filename on the server. Use this or image.url, not both.',
                        ],
                        'image.url' => [
                            'type'        => 'string',
                            'description' => 'URL to download the logo from. The server saves it and stores the filename in manufacturer_logo. Use this or manufacturer_logo, not both.',
                        ],
                        'manufacturer_publish' => [
                            'type'        => 'integer',
                            'description' => 'Published: 1 or 0',
                            'enum'        => [0, 1],
                        ],
                        'ordering' => [
                            'type'        => 'integer',
                            'description' => 'Sort order',
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'manufacturers_delete',
                'description' => 'Delete a manufacturer by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Manufacturer ID',
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
            case 'manufacturers_list':
                return $this->list();
            case 'manufacturers_get':
                return $this->get((int) $args['id']);
            case 'manufacturers_add':
                return $this->add($args);
            case 'manufacturers_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'manufacturers_delete':
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
        $data = $this->client->request('GET', '/api/index.php/v1/shop/manufacturers');
        return $this->formatResponse($data);
    }

    private function get(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/manufacturers/{$id}");
        return $this->formatResponse($data);
    }

    private function add(array $args): array
    {
        $body = [];

        if (isset($args['name']))              { $body['name']              = $args['name']; }
        if (isset($args['short_description'])) { $body['short_description'] = $args['short_description']; }
        if (isset($args['description']))       { $body['description']       = $args['description']; }

        foreach (['alias', 'manufacturer_url', 'manufacturer_logo', 'image.url', 'manufacturer_publish', 'ordering'] as $field) {
            if (isset($args[$field])) {
                $body[$field] = $args[$field];
            }
        }

        $data = $this->client->request('POST', '/api/index.php/v1/shop/manufacturers', $body);
        return $this->formatResponse($data);
    }

    private function edit(int $id, array $args): array
    {
        $body = [];

        if (isset($args['name']))              { $body['name']              = $args['name']; }
        if (isset($args['short_description'])) { $body['short_description'] = $args['short_description']; }
        if (isset($args['description']))       { $body['description']       = $args['description']; }

        foreach (['alias', 'manufacturer_url', 'manufacturer_logo', 'image.url', 'manufacturer_publish', 'ordering'] as $field) {
            if (isset($args[$field])) {
                $body[$field] = $args[$field];
            }
        }

        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/manufacturers/{$id}", $body);
        return $this->formatResponse($data);
    }

    private function delete(int $id): array
    {
        $data = $this->client->request('DELETE', "/api/index.php/v1/shop/manufacturers/{$id}");
        return [
            [
                'type' => 'text',
                'text' => "Manufacturer #{$id} deleted successfully.",
            ],
        ];
    }

    /**
     * Convert JSON:API response to MCP content array.
     */
    private function formatResponse(array $data): array
    {
        // List response: data is an array of items
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

        // Single item response
        if (isset($data['data'])) {
            return [
                [
                    'type' => 'text',
                    'text' => $this->formatItem($data['data']),
                ],
            ];
        }

        // Fallback: return raw JSON
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

        $name              = $attr['name'] ?? '(no name)';
        $alias             = $attr['alias'] ?? '';
        $short_description = $attr['short_description'] ?? '';
        $description       = $attr['description'] ?? '';
        $url               = $attr['manufacturer_url'] ?? '';
        $logo              = $attr['manufacturer_logo'] ?? '';
        $published         = isset($attr['manufacturer_publish']) ? ($attr['manufacturer_publish'] ? 'yes' : 'no') : '?';
        $ordering          = $attr['ordering'] ?? '';

        $parts = ["ID: {$id}", "Name: {$name}", "Published: {$published}"];
        if ($alias)             { $parts[] = "Alias: {$alias}"; }
        if ($short_description) { $parts[] = "Short description: {$short_description}"; }
        if ($description)       { $parts[] = "Description: {$description}"; }
        if ($url)               { $parts[] = "URL: {$url}"; }
        if ($logo)              { $parts[] = "Logo: {$logo}"; }
        if ($ordering !== '')   { $parts[] = "Ordering: {$ordering}"; }

        return implode("\n", $parts);
    }
}
