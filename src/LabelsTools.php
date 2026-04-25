<?php

class LabelsTools
{
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function getDefinitions(): array
    {
        return [
            [
                'name'        => 'labels_list',
                'description' => 'Get a list of all product labels/badges from JoomShopping.',
                'inputSchema' => ['type' => 'object', 'properties' => (object)[], 'required' => []],
            ],
            [
                'name'        => 'labels_get',
                'description' => 'Get a single product label by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Label ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'labels_add',
                'description' => 'Add a new product label/badge to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name'         => ['type' => 'string', 'description' => 'Label name (required)'],
                        'image'        => ['type' => 'string', 'description' => 'Existing image filename on the server'],
                        'image.base64' => ['type' => 'string', 'description' => 'Base64 data URI: data:image/jpeg;base64,...'],
                        'image.url'    => ['type' => 'string', 'description' => 'URL to download the image from'],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'labels_edit',
                'description' => 'Edit an existing product label in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'           => ['type' => 'integer', 'description' => 'Label ID (required)'],
                        'name'         => ['type' => 'string',  'description' => 'New label name'],
                        'image'        => ['type' => 'string',  'description' => 'Existing image filename on the server'],
                        'image.base64' => ['type' => 'string',  'description' => 'Base64 data URI: data:image/jpeg;base64,...'],
                        'image.url'    => ['type' => 'string',  'description' => 'URL to download the image from'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'labels_delete',
                'description' => 'Delete a product label by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Label ID']],
                    'required'   => ['id'],
                ],
            ],
        ];
    }

    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'labels_list':
                return $this->list();
            case 'labels_get':
                return $this->get((int) $args['id']);
            case 'labels_add':
                return $this->add($args);
            case 'labels_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'labels_delete':
                return $this->delete((int) $args['id']);
            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    private function list(): array
    {
        return $this->formatResponse($this->client->request('GET', '/api/index.php/v1/shop/productlabels'));
    }

    private function get(int $id): array
    {
        return $this->formatResponse($this->client->request('GET', "/api/index.php/v1/shop/productlabels/{$id}"));
    }

    private function add(array $args): array
    {
        $body = $this->pick($args, ['name', 'image', 'image.base64', 'image.url']);
        return $this->formatResponse($this->client->request('POST', '/api/index.php/v1/shop/productlabels', $body));
    }

    private function edit(int $id, array $args): array
    {
        $body = $this->pick($args, ['name', 'image', 'image.base64', 'image.url']);
        return $this->formatResponse($this->client->request('PATCH', "/api/index.php/v1/shop/productlabels/{$id}", $body));
    }

    private function delete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/productlabels/{$id}");
        return [['type' => 'text', 'text' => "Label #{$id} deleted successfully."]];
    }

    private function pick(array $args, array $fields): array
    {
        $body = [];
        foreach ($fields as $f) {
            if (isset($args[$f])) $body[$f] = $args[$f];
        }
        return $body;
    }

    private function formatResponse(array $data): array
    {
        if (isset($data['data'][0])) {
            return [['type' => 'text', 'text' => implode("\n\n", array_map([$this, 'formatItem'], $data['data']))]];
        }
        if (isset($data['data'])) {
            return [['type' => 'text', 'text' => $this->formatItem($data['data'])]];
        }
        return [['type' => 'text', 'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]];
    }

    private function formatItem(array $item): string
    {
        $id   = $item['id'] ?? '?';
        $attr = $item['attributes'] ?? $item;
        $parts = [
            "ID: {$id}",
            "Name: " . ($attr['name'] ?? '(no name)'),
        ];
        if (!empty($attr['image'])) { $parts[] = "Image: {$attr['image']}"; }
        return implode("\n", $parts);
    }
}
