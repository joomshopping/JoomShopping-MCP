<?php

class TaxesTools
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
                'name'        => 'taxes_list',
                'description' => 'Get a list of all taxes from JoomShopping.',
                'inputSchema' => ['type' => 'object', 'properties' => (object)[], 'required' => []],
            ],
            [
                'name'        => 'taxes_get',
                'description' => 'Get a single tax by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Tax ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'taxes_add',
                'description' => 'Add a new tax to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'tax_name'  => ['type' => 'string', 'description' => 'Tax name, e.g. "VAT 20%" (required)'],
                        'tax_value' => ['type' => 'number', 'description' => 'Tax rate in percent (required)'],
                        'ordering'  => ['type' => 'integer', 'description' => 'Sort order'],
                    ],
                    'required' => ['tax_name', 'tax_value'],
                ],
            ],
            [
                'name'        => 'taxes_edit',
                'description' => 'Edit an existing tax in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'        => ['type' => 'integer', 'description' => 'Tax ID (required)'],
                        'tax_name'  => ['type' => 'string',  'description' => 'New tax name'],
                        'tax_value' => ['type' => 'number',  'description' => 'New tax rate in percent'],
                        'ordering'  => ['type' => 'integer', 'description' => 'Sort order'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'taxes_delete',
                'description' => 'Delete a tax by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Tax ID']],
                    'required'   => ['id'],
                ],
            ],
        ];
    }

    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'taxes_list':
                return $this->list();
            case 'taxes_get':
                return $this->get((int) $args['id']);
            case 'taxes_add':
                return $this->add($args);
            case 'taxes_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'taxes_delete':
                return $this->delete((int) $args['id']);
            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    private function list(): array
    {
        return $this->formatResponse($this->client->request('GET', '/api/index.php/v1/shop/taxs'));
    }

    private function get(int $id): array
    {
        return $this->formatResponse($this->client->request('GET', "/api/index.php/v1/shop/taxs/{$id}"));
    }

    private function add(array $args): array
    {
        $body = $this->pick($args, ['tax_name', 'tax_value', 'ordering']);
        return $this->formatResponse($this->client->request('POST', '/api/index.php/v1/shop/taxs', $body));
    }

    private function edit(int $id, array $args): array
    {
        $body = $this->pick($args, ['tax_name', 'tax_value', 'ordering']);
        return $this->formatResponse($this->client->request('PATCH', "/api/index.php/v1/shop/taxs/{$id}", $body));
    }

    private function delete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/taxs/{$id}");
        return [['type' => 'text', 'text' => "Tax #{$id} deleted successfully."]];
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
            "Name: " . ($attr['tax_name'] ?? '(no name)'),
            "Rate: " . ($attr['tax_value'] ?? '?') . '%',
        ];
        if (isset($attr['ordering'])) { $parts[] = "Ordering: {$attr['ordering']}"; }
        return implode("\n", $parts);
    }
}
