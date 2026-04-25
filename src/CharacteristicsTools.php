<?php

/**
 * Tools for managing product characteristics (extra fields and their values).
 *
 * API resources:
 *   /fields       — field definitions (e.g. "Doors", "Tire Width")
 *   /fieldvalues  — predefined values for list-type fields (e.g. "4", "195")
 *   /productfields — assignments of field values to a specific product
 */
class CharacteristicsTools
{
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    // -------------------------------------------------------------------------
    // Definitions
    // -------------------------------------------------------------------------

    public function getDefinitions(): array
    {
        return [
            // --- /fields ---
            [
                'name'        => 'characteristics_fields_list',
                'description' => 'Get all characteristic field definitions from JoomShopping (e.g. "Doors", "Tire Width").',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => (object)[],
                    'required'   => [],
                ],
            ],
            [
                'name'        => 'characteristics_fields_get',
                'description' => 'Get a single characteristic field definition by ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Field ID'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'characteristics_fields_add',
                'description' => 'Add a new characteristic field definition to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name'     => ['type' => 'string',  'description' => 'Field label (required)'],
                        'type'     => ['type' => 'integer', 'description' => 'Input type: 0=Select (default, most common), 1=Text (deprecated)', 'enum' => [0, 1]],
                        'publish'  => ['type' => 'integer', 'description' => 'Published: 1 or 0', 'enum' => [0, 1]],
                        'allcats'  => ['type' => 'integer', 'description' => 'Show in all categories: 1 or 0 (default: 1)', 'enum' => [0, 1]],
                        'ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'group'    => ['type' => 'integer', 'description' => 'Group ID'],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'characteristics_fields_edit',
                'description' => 'Edit an existing characteristic field definition.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'       => ['type' => 'integer', 'description' => 'Field ID (required)'],
                        'name'     => ['type' => 'string',  'description' => 'New label'],
                        'type'     => ['type' => 'integer', 'description' => 'Input type: 0=Select (default, most common), 1=Text (deprecated)', 'enum' => [0, 1]],
                        'publish'  => ['type' => 'integer', 'description' => 'Published: 1 or 0', 'enum' => [0, 1]],
                        'allcats'  => ['type' => 'integer', 'description' => 'Show in all categories: 1 or 0 (default: 1)', 'enum' => [0, 1]],
                        'ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'group'    => ['type' => 'integer', 'description' => 'Group ID'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'characteristics_fields_delete',
                'description' => 'Delete a characteristic field definition by ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Field ID'],
                    ],
                    'required' => ['id'],
                ],
            ],

            // --- /fieldvalues ---
            [
                'name'        => 'characteristics_fieldvalues_list',
                'description' => 'Get all predefined characteristic values from JoomShopping. Optionally filter by field ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'field_id' => ['type' => 'integer', 'description' => 'Filter by parent field ID'],
                    ],
                    'required' => [],
                ],
            ],
            [
                'name'        => 'characteristics_fieldvalues_get',
                'description' => 'Get a single predefined characteristic value by ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Value ID'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'characteristics_fieldvalues_add',
                'description' => 'Add a new predefined value for a characteristic field (e.g. value "4" for field "Doors").',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name'     => ['type' => 'string',  'description' => 'Value label (required)'],
                        'field_id' => ['type' => 'integer', 'description' => 'Parent field ID (required)'],
                        'ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'publish'  => ['type' => 'integer', 'description' => 'Published: 1 or 0', 'enum' => [0, 1]],
                    ],
                    'required' => ['name', 'field_id'],
                ],
            ],
            [
                'name'        => 'characteristics_fieldvalues_edit',
                'description' => 'Edit an existing predefined characteristic value.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'       => ['type' => 'integer', 'description' => 'Value ID (required)'],
                        'name'     => ['type' => 'string',  'description' => 'New label'],
                        'field_id' => ['type' => 'integer', 'description' => 'New parent field ID'],
                        'ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                        'publish'  => ['type' => 'integer', 'description' => 'Published: 1 or 0', 'enum' => [0, 1]],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'characteristics_fieldvalues_delete',
                'description' => 'Delete a predefined characteristic value by ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Value ID'],
                    ],
                    'required' => ['id'],
                ],
            ],

            // --- /productfields ---
            [
                'name'        => 'characteristics_product_get',
                'description' => 'Get characteristic field values assigned to a product. Returns an object where keys are field IDs and values are fieldvalue IDs (or raw strings).',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'product_id' => ['type' => 'integer', 'description' => 'Product ID'],
                    ],
                    'required' => ['product_id'],
                ],
            ],
            [
                'name'        => 'characteristics_product_set',
                'description' => 'Set (create) characteristic values for a product. The "fields" object maps field IDs to fieldvalue IDs. Example: {"1": "4", "2": "165"}.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'product_id' => ['type' => 'integer', 'description' => 'Product ID (required)'],
                        'fields'     => [
                            'type'        => 'object',
                            'description' => 'Map of field_id → fieldvalue_id. Example: {"1": "4", "2": "165", "4": 0}',
                        ],
                    ],
                    'required' => ['product_id', 'fields'],
                ],
            ],
            [
                'name'        => 'characteristics_product_update',
                'description' => 'Update characteristic values for a product (PATCH). Only provided fields are changed.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'product_id' => ['type' => 'integer', 'description' => 'Product ID (required)'],
                        'fields'     => [
                            'type'        => 'object',
                            'description' => 'Map of field_id → fieldvalue_id. Example: {"1": "4", "2": "165"}',
                        ],
                    ],
                    'required' => ['product_id', 'fields'],
                ],
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Dispatch
    // -------------------------------------------------------------------------

    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'characteristics_fields_list':
                return $this->fieldsList();
            case 'characteristics_fields_get':
                return $this->fieldsGet((int) $args['id']);
            case 'characteristics_fields_add':
                return $this->fieldsAdd($args);
            case 'characteristics_fields_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->fieldsEdit($id, $args);
            case 'characteristics_fields_delete':
                return $this->fieldsDelete((int) $args['id']);

            case 'characteristics_fieldvalues_list':
                return $this->fieldvaluesList($args);
            case 'characteristics_fieldvalues_get':
                return $this->fieldvaluesGet((int) $args['id']);
            case 'characteristics_fieldvalues_add':
                return $this->fieldvaluesAdd($args);
            case 'characteristics_fieldvalues_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->fieldvaluesEdit($id, $args);
            case 'characteristics_fieldvalues_delete':
                return $this->fieldvaluesDelete((int) $args['id']);

            case 'characteristics_product_get':
                return $this->productGet((int) $args['product_id']);
            case 'characteristics_product_set':
                return $this->productSet((int) $args['product_id'], $args['fields']);
            case 'characteristics_product_update':
                return $this->productUpdate((int) $args['product_id'], $args['fields']);

            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    // -------------------------------------------------------------------------
    // /fields
    // -------------------------------------------------------------------------

    private function fieldsList(): array
    {
        $data = $this->client->request('GET', '/api/index.php/v1/shop/fields');
        return $this->formatFieldsList($data);
    }

    private function fieldsGet(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/fields/{$id}");
        return $this->formatFieldItem($data);
    }

    private function fieldsAdd(array $args): array
    {
        $body = $this->pickKeys($args, ['name', 'type', 'publish', 'allcats', 'ordering', 'group']);
        $data = $this->client->request('POST', '/api/index.php/v1/shop/fields', $body);
        return $this->formatFieldItem($data);
    }

    private function fieldsEdit(int $id, array $args): array
    {
        $body = $this->pickKeys($args, ['name', 'type', 'publish', 'allcats', 'ordering', 'group']);
        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/fields/{$id}", $body);
        return $this->formatFieldItem($data);
    }

    private function fieldsDelete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/fields/{$id}");
        return [['type' => 'text', 'text' => "Field #{$id} deleted successfully."]];
    }

    // -------------------------------------------------------------------------
    // /fieldvalues
    // -------------------------------------------------------------------------

    private function fieldvaluesList(array $args): array
    {
        $url = '/api/index.php/v1/shop/fieldvalues';
        if (!empty($args['field_id'])) {
            $url .= '?' . http_build_query(['filter[field_id]' => $args['field_id']]);
        }
        $data = $this->client->request('GET', $url);
        return $this->formatFieldvaluesList($data);
    }

    private function fieldvaluesGet(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/fieldvalues/{$id}");
        return $this->formatFieldvalueItem($data);
    }

    private function fieldvaluesAdd(array $args): array
    {
        $body = $this->pickKeys($args, ['name', 'field_id', 'ordering', 'publish']);
        $data = $this->client->request('POST', '/api/index.php/v1/shop/fieldvalues', $body);
        return $this->formatFieldvalueItem($data);
    }

    private function fieldvaluesEdit(int $id, array $args): array
    {
        $body = $this->pickKeys($args, ['name', 'field_id', 'ordering', 'publish']);
        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/fieldvalues/{$id}", $body);
        return $this->formatFieldvalueItem($data);
    }

    private function fieldvaluesDelete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/fieldvalues/{$id}");
        return [['type' => 'text', 'text' => "Field value #{$id} deleted successfully."]];
    }

    // -------------------------------------------------------------------------
    // /productfields
    // -------------------------------------------------------------------------

    private function productGet(int $productId): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/productfields/{$productId}");

        $attr   = $data['data']['attributes'] ?? $data['data'] ?? $data;
        $fields = $attr['fields'] ?? [];

        if (empty($fields)) {
            return [['type' => 'text', 'text' => "Product #{$productId}: no characteristics assigned."]];
        }

        $lines = ["Product #{$productId} characteristics:"];
        foreach ($fields as $fieldId => $valueId) {
            $lines[] = "  field_id={$fieldId} → value_id={$valueId}";
        }

        return [['type' => 'text', 'text' => implode("\n", $lines)]];
    }

    private function productSet(int $productId, array $fields): array
    {
        $body = ['product_id' => $productId, 'fields' => $fields];
        $data = $this->client->request('POST', '/api/index.php/v1/shop/productfields', $body);
        return $this->formatProductfieldsResponse($data, $productId);
    }

    private function productUpdate(int $productId, array $fields): array
    {
        $body = ['fields' => $fields];
        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/productfields/{$productId}", $body);
        return $this->formatProductfieldsResponse($data, $productId);
    }

    // -------------------------------------------------------------------------
    // Formatting helpers
    // -------------------------------------------------------------------------

    private function formatFieldsList(array $data): array
    {
        $items = $data['data'] ?? [];
        if (empty($items)) {
            return [['type' => 'text', 'text' => 'No characteristic fields found.']];
        }

        // Single item (not a list)
        if (isset($items['id'])) {
            $items = [$items];
        }

        $lines = [];
        foreach ($items as $item) {
            $attr     = $item['attributes'] ?? $item;
            $id       = $item['id'] ?? ($attr['id'] ?? '?');
            $name     = $attr['name'] ?? '(no name)';
            $type     = $attr['type'] ?? '';
            $publish  = isset($attr['publish']) ? ($attr['publish'] ? 'yes' : 'no') : '?';
            $allcats  = isset($attr['allcats']) ? ($attr['allcats'] ? 'yes' : 'no') : '?';
            $ordering = $attr['ordering'] ?? '';

            $line = "ID: {$id} | {$name}";
            if ($type !== '')     { $line .= " | type={$type}"; }
            if ($ordering !== '') { $line .= " | ordering={$ordering}"; }
            $line .= " | published={$publish} | allcats={$allcats}";
            $lines[] = $line;
        }

        return [['type' => 'text', 'text' => implode("\n", $lines)]];
    }

    private function formatFieldItem(array $data): array
    {
        $item = $data['data'] ?? $data;
        $attr = $item['attributes'] ?? $item;
        $id   = $item['id'] ?? ($attr['id'] ?? '?');

        $parts = ["ID: {$id}"];
        if (isset($attr['name']))     { $parts[] = "Name: {$attr['name']}"; }
        if (isset($attr['type']))     { $parts[] = "Type: {$attr['type']}"; }
        if (isset($attr['publish']))  { $parts[] = "Published: " . ($attr['publish'] ? 'yes' : 'no'); }
        if (isset($attr['allcats']))  { $parts[] = "All categories: " . ($attr['allcats'] ? 'yes' : 'no'); }
        if (isset($attr['ordering'])) { $parts[] = "Ordering: {$attr['ordering']}"; }
        if (isset($attr['group']))    { $parts[] = "Group ID: {$attr['group']}"; }

        return [['type' => 'text', 'text' => implode("\n", $parts)]];
    }

    private function formatFieldvaluesList(array $data): array
    {
        $items = $data['data'] ?? [];
        if (empty($items)) {
            return [['type' => 'text', 'text' => 'No characteristic values found.']];
        }

        if (isset($items['id'])) {
            $items = [$items];
        }

        $lines = [];
        foreach ($items as $item) {
            $attr     = $item['attributes'] ?? $item;
            $id       = $item['id'] ?? ($attr['id'] ?? '?');
            $name     = $attr['name'] ?? '(no name)';
            $fieldId  = $attr['field_id'] ?? '?';
            $publish  = isset($attr['publish']) ? ($attr['publish'] ? 'yes' : 'no') : '?';
            $ordering = $attr['ordering'] ?? '';

            $line = "ID: {$id} | {$name} | field_id={$fieldId} | published={$publish}";
            if ($ordering !== '') { $line .= " | ordering={$ordering}"; }
            $lines[] = $line;
        }

        return [['type' => 'text', 'text' => implode("\n", $lines)]];
    }

    private function formatFieldvalueItem(array $data): array
    {
        $item = $data['data'] ?? $data;
        $attr = $item['attributes'] ?? $item;
        $id   = $item['id'] ?? ($attr['id'] ?? '?');

        $parts = ["ID: {$id}"];
        if (isset($attr['name']))     { $parts[] = "Name: {$attr['name']}"; }
        if (isset($attr['field_id'])) { $parts[] = "Field ID: {$attr['field_id']}"; }
        if (isset($attr['publish']))  { $parts[] = "Published: " . ($attr['publish'] ? 'yes' : 'no'); }
        if (isset($attr['ordering'])) { $parts[] = "Ordering: {$attr['ordering']}"; }

        return [['type' => 'text', 'text' => implode("\n", $parts)]];
    }

    private function formatProductfieldsResponse(array $data, int $productId): array
    {
        $attr   = $data['data']['attributes'] ?? $data['data'] ?? [];
        $fields = $attr['fields'] ?? [];

        if (empty($fields)) {
            return [['type' => 'text', 'text' => "Product #{$productId} characteristics saved (no values returned)."]];
        }

        $lines = ["Product #{$productId} characteristics saved:"];
        foreach ($fields as $fieldId => $valueId) {
            $lines[] = "  field_id={$fieldId} → value_id={$valueId}";
        }

        return [['type' => 'text', 'text' => implode("\n", $lines)]];
    }

    // -------------------------------------------------------------------------
    // Utility
    // -------------------------------------------------------------------------

    private function pickKeys(array $source, array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($source[$key])) {
                $result[$key] = $source[$key];
            }
        }
        return $result;
    }
}
