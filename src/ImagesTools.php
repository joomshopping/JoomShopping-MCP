<?php

class ImagesTools
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
                'name'        => 'images_list',
                'description' => 'List uploaded image/file names from a JoomShopping server directory.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'display' => [
                            'type'        => 'string',
                            'description' => 'Directory to browse. Default: product.',
                            'enum'        => ['product', 'category', 'manufacturer', 'attribute', 'video', 'demofiles', 'salefiles'],
                        ],
                        'limit' => [
                            'type'        => 'integer',
                            'description' => 'Number of items per page.',
                        ],
                        'start' => [
                            'type'        => 'integer',
                            'description' => 'Offset (default 0).',
                        ],
                    ],
                    'required' => [],
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
            case 'images_list':
                return $this->list($args);
            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    // -------------------------------------------------------------------------
    // Private API methods
    // -------------------------------------------------------------------------

    private function list(array $args): array
    {
        $query = [];

        if (isset($args['display'])) {
            $query['list[display]'] = $args['display'];
        }
        if (isset($args['limit'])) {
            $query['list[limit]'] = (int) $args['limit'];
        }
        if (isset($args['start'])) {
            $query['list[start]'] = (int) $args['start'];
        }

        $path = '/api/index.php/v1/shop/images';
        if ($query) {
            $path .= '?' . http_build_query($query);
        }

        $data = $this->client->request('GET', $path);
        return $this->formatResponse($data, $args['display'] ?? 'product');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Convert JSON:API response to MCP content array.
     */
    private function formatResponse(array $data, string $display): array
    {
        if (!isset($data['data']) || !is_array($data['data'])) {
            return [
                [
                    'type' => 'text',
                    'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                ],
            ];
        }

        $items = $data['data'];

        if (empty($items)) {
            return [
                [
                    'type' => 'text',
                    'text' => "No images found in '{$display}' directory.",
                ],
            ];
        }

        $lines = ["Images in '{$display}' directory (" . count($items) . " files):"];
        foreach ($items as $item) {
            $id   = $item['id'] ?? '?';
            $name = $item['attributes']['name'] ?? '(unknown)';
            $lines[] = "  [{$id}] {$name}";
        }

        return [
            [
                'type' => 'text',
                'text' => implode("\n", $lines),
            ],
        ];
    }
}
