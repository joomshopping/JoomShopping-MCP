<?php

class AttributesTools
{
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function getDefinitions(): array
    {
        return [
            // ── attributes ────────────────────────────────────────────────
            [
                'name'        => 'attributes_list',
                'description' => 'Get a list of all attributes from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => (object)[],
                    'required'   => [],
                ],
            ],
            [
                'name'        => 'attributes_get',
                'description' => 'Get a single attribute by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Attribute ID'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'attributes_add',
                'description' => 'Add a new attribute to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name'          => ['type' => 'string',  'description' => 'Attribute name (required)'],
                        'attr_type'     => ['type' => 'integer', 'description' => 'Attribute type'],
                        'attr_ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'independent'   => ['type' => 'integer', 'description' => 'Independent: 0 = attribute for product variant (has code, price, stock, dimensions); 1 = simple list', 'enum' => [0, 1]],
                        'allcats'       => ['type' => 'integer', 'description' => 'Show in all categories: 1/0', 'enum' => [0, 1]],
                        'cats'          => ['type' => 'string',  'description' => 'JSON array of category IDs (when allcats=0)'],
                        'group'         => ['type' => 'integer', 'description' => 'Group ID'],
                        'publish'       => ['type' => 'integer', 'description' => 'Published: 1/0', 'enum' => [0, 1]],
                        'required'      => ['type' => 'integer', 'description' => 'Required: 1/0', 'enum' => [0, 1]],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'attributes_edit',
                'description' => 'Edit an existing attribute in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'            => ['type' => 'integer', 'description' => 'Attribute ID (required)'],
                        'name'          => ['type' => 'string',  'description' => 'New name'],
                        'attr_type'     => ['type' => 'integer', 'description' => 'Attribute type'],
                        'attr_ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'independent'   => ['type' => 'integer', 'description' => 'Independent: 0 = attribute for product variant (has code, price, stock, dimensions); 1 = simple list', 'enum' => [0, 1]],
                        'allcats'       => ['type' => 'integer', 'description' => 'Show in all categories: 1/0', 'enum' => [0, 1]],
                        'cats'          => ['type' => 'string',  'description' => 'JSON array of category IDs (when allcats=0)'],
                        'group'         => ['type' => 'integer', 'description' => 'Group ID'],
                        'publish'       => ['type' => 'integer', 'description' => 'Published: 1/0', 'enum' => [0, 1]],
                        'required'      => ['type' => 'integer', 'description' => 'Required: 1/0', 'enum' => [0, 1]],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'attributes_delete',
                'description' => 'Delete an attribute by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Attribute ID'],
                    ],
                    'required' => ['id'],
                ],
            ],

            // ── attributesvalues ──────────────────────────────────────────
            [
                'name'        => 'attributesvalues_list',
                'description' => 'Get a list of all attribute values from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => (object)[],
                    'required'   => [],
                ],
            ],
            [
                'name'        => 'attributesvalues_get',
                'description' => 'Get a single attribute value by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Value ID'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'attributesvalues_add',
                'description' => 'Add a new attribute value to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name'            => ['type' => 'string',  'description' => 'Value label (required)'],
                        'attr_id'         => ['type' => 'integer', 'description' => 'Parent attribute ID (required)'],
                        'publish'         => ['type' => 'integer', 'description' => 'Published: 1/0', 'enum' => [0, 1]],
                        'value_ordering'  => ['type' => 'integer', 'description' => 'Sort order'],
                        'image'           => ['type' => 'string',  'description' => 'Existing image filename on the server'],
                        'image.base64'    => ['type' => 'string',  'description' => 'Base64 data URI: data:image/jpeg;base64,...'],
                        'image.url'       => ['type' => 'string',  'description' => 'URL to download the image from'],
                    ],
                    'required' => ['name', 'attr_id'],
                ],
            ],
            [
                'name'        => 'attributesvalues_edit',
                'description' => 'Edit an existing attribute value in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'             => ['type' => 'integer', 'description' => 'Value ID (required)'],
                        'name'           => ['type' => 'string',  'description' => 'New value label'],
                        'attr_id'        => ['type' => 'integer', 'description' => 'Parent attribute ID'],
                        'publish'        => ['type' => 'integer', 'description' => 'Published: 1/0', 'enum' => [0, 1]],
                        'value_ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'image'          => ['type' => 'string',  'description' => 'Existing image filename on the server'],
                        'image.base64'   => ['type' => 'string',  'description' => 'Base64 data URI: data:image/jpeg;base64,...'],
                        'image.url'      => ['type' => 'string',  'description' => 'URL to download the image from'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'attributesvalues_delete',
                'description' => 'Delete an attribute value by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Value ID'],
                    ],
                    'required' => ['id'],
                ],
            ],
        ];
    }

    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'attributes_list':
                return $this->attrList();
            case 'attributes_get':
                return $this->attrGet((int) $args['id']);
            case 'attributes_add':
                return $this->attrAdd($args);
            case 'attributes_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->attrEdit($id, $args);
            case 'attributes_delete':
                return $this->attrDelete((int) $args['id']);

            case 'attributesvalues_list':
                return $this->valList();
            case 'attributesvalues_get':
                return $this->valGet((int) $args['id']);
            case 'attributesvalues_add':
                return $this->valAdd($args);
            case 'attributesvalues_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->valEdit($id, $args);
            case 'attributesvalues_delete':
                return $this->valDelete((int) $args['id']);

            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    // ── attributes ────────────────────────────────────────────────────────

    private function attrList(): array
    {
        $data = $this->client->request('GET', '/api/index.php/v1/shop/attributes');
        return $this->formatResponse($data, 'attribute');
    }

    private function attrGet(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/attributes/{$id}");
        return $this->formatResponse($data, 'attribute');
    }

    private function attrAdd(array $args): array
    {
        $fields = ['name', 'attr_type', 'attr_ordering', 'independent', 'allcats', 'cats', 'group', 'publish', 'required'];
        $data = $this->client->request('POST', '/api/index.php/v1/shop/attributes', $this->pick($args, $fields));
        return $this->formatResponse($data, 'attribute');
    }

    private function attrEdit(int $id, array $args): array
    {
        $fields = ['name', 'attr_type', 'attr_ordering', 'independent', 'allcats', 'cats', 'group', 'publish', 'required'];
        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/attributes/{$id}", $this->pick($args, $fields));
        return $this->formatResponse($data, 'attribute');
    }

    private function attrDelete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/attributes/{$id}");
        return [['type' => 'text', 'text' => "Attribute #{$id} deleted successfully."]];
    }

    // ── attributesvalues ──────────────────────────────────────────────────

    private function valList(): array
    {
        $data = $this->client->request('GET', '/api/index.php/v1/shop/attributesvalues');
        return $this->formatResponse($data, 'value');
    }

    private function valGet(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/attributesvalues/{$id}");
        return $this->formatResponse($data, 'value');
    }

    private function valAdd(array $args): array
    {
        $fields = ['name', 'attr_id', 'publish', 'value_ordering', 'image', 'image.base64', 'image.url'];
        $data = $this->client->request('POST', '/api/index.php/v1/shop/attributesvalues', $this->pick($args, $fields));
        return $this->formatResponse($data, 'value');
    }

    private function valEdit(int $id, array $args): array
    {
        $fields = ['name', 'attr_id', 'publish', 'value_ordering', 'image', 'image.base64', 'image.url'];
        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/attributesvalues/{$id}", $this->pick($args, $fields));
        return $this->formatResponse($data, 'value');
    }

    private function valDelete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/attributesvalues/{$id}");
        return [['type' => 'text', 'text' => "Attribute value #{$id} deleted successfully."]];
    }

    // ── helpers ───────────────────────────────────────────────────────────

    private function pick(array $args, array $fields): array
    {
        $body = [];
        foreach ($fields as $f) {
            if (isset($args[$f])) {
                $body[$f] = $args[$f];
            }
        }
        return $body;
    }

    private function formatResponse(array $data, string $kind): array
    {
        if (isset($data['data']) && is_array($data['data']) && isset($data['data'][0])) {
            $lines = [];
            foreach ($data['data'] as $item) {
                $lines[] = $this->formatItem($item, $kind);
            }
            return [['type' => 'text', 'text' => implode("\n\n", $lines)]];
        }

        if (isset($data['data'])) {
            return [['type' => 'text', 'text' => $this->formatItem($data['data'], $kind)]];
        }

        return [['type' => 'text', 'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]];
    }

    private function formatItem(array $item, string $kind): string
    {
        $id   = $item['id'] ?? '?';
        $attr = $item['attributes'] ?? $item;

        if ($kind === 'attribute') {
            $parts = [
                "ID: {$id}",
                "Name: " . ($attr['name'] ?? '(no name)'),
                "Type: " . ($attr['attr_type'] ?? ''),
                "Published: " . (isset($attr['publish']) ? ($attr['publish'] ? 'yes' : 'no') : '?'),
            ];
            if (isset($attr['group']))         { $parts[] = "Group: {$attr['group']}"; }
            if (isset($attr['attr_ordering'])) { $parts[] = "Ordering: {$attr['attr_ordering']}"; }
            if (isset($attr['independent']))   { $parts[] = "Independent: {$attr['independent']}"; }
            if (isset($attr['allcats']))       { $parts[] = "All categories: {$attr['allcats']}"; }
            if (!empty($attr['cats']))         { $parts[] = "Categories: {$attr['cats']}"; }
        } else {
            $parts = [
                "ID: {$id}",
                "Name: " . ($attr['name'] ?? '(no name)'),
                "Attr ID: " . ($attr['attr_id'] ?? '?'),
                "Published: " . (isset($attr['publish']) ? ($attr['publish'] ? 'yes' : 'no') : '?'),
            ];
            if (isset($attr['value_ordering'])) { $parts[] = "Ordering: {$attr['value_ordering']}"; }
            if (!empty($attr['image']))          { $parts[] = "Image: {$attr['image']}"; }
        }

        return implode("\n", $parts);
    }
}
