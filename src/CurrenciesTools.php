<?php

class CurrenciesTools
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
                'name'        => 'currencies_list',
                'description' => 'Get a list of all currencies from JoomShopping.',
                'inputSchema' => ['type' => 'object', 'properties' => (object)[], 'required' => []],
            ],
            [
                'name'        => 'currencies_get',
                'description' => 'Get a single currency by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Currency ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'currencies_add',
                'description' => 'Add a new currency to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'currency_name'     => ['type' => 'string', 'description' => 'Currency name, e.g. "Euro" (required)'],
                        'currency_code'     => ['type' => 'string', 'description' => 'Currency symbol, e.g. "€" (required)'],
                        'currency_code_iso' => ['type' => 'string', 'description' => 'ISO alpha-3 code, e.g. "EUR"'],
                        'currency_code_num' => ['type' => 'string', 'description' => 'ISO numeric code, e.g. "978"'],
                        'currency_value'    => ['type' => 'number', 'description' => 'Exchange rate'],
                        'currency_publish'  => ['type' => 'integer', 'description' => 'Published: 1/0', 'enum' => [0, 1]],
                        'currency_ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                    ],
                    'required' => ['currency_name', 'currency_code'],
                ],
            ],
            [
                'name'        => 'currencies_edit',
                'description' => 'Edit an existing currency in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'                => ['type' => 'integer', 'description' => 'Currency ID (required)'],
                        'currency_name'     => ['type' => 'string',  'description' => 'Currency name'],
                        'currency_code'     => ['type' => 'string',  'description' => 'Currency symbol'],
                        'currency_code_iso' => ['type' => 'string',  'description' => 'ISO alpha-3 code'],
                        'currency_code_num' => ['type' => 'string',  'description' => 'ISO numeric code'],
                        'currency_value'    => ['type' => 'number',  'description' => 'Exchange rate'],
                        'currency_publish'  => ['type' => 'integer', 'description' => 'Published: 1/0', 'enum' => [0, 1]],
                        'currency_ordering' => ['type' => 'integer', 'description' => 'Sort order'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'currencies_delete',
                'description' => 'Delete a currency by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Currency ID']],
                    'required'   => ['id'],
                ],
            ],
        ];
    }

    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'currencies_list':
                return $this->list();
            case 'currencies_get':
                return $this->get((int) $args['id']);
            case 'currencies_add':
                return $this->add($args);
            case 'currencies_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'currencies_delete':
                return $this->delete((int) $args['id']);
            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    private function list(): array
    {
        return $this->formatResponse($this->client->request('GET', '/api/index.php/v1/shop/currencies'));
    }

    private function get(int $id): array
    {
        return $this->formatResponse($this->client->request('GET', "/api/index.php/v1/shop/currencies/{$id}"));
    }

    private function add(array $args): array
    {
        $fields = ['currency_name', 'currency_code', 'currency_code_iso', 'currency_code_num', 'currency_value', 'currency_publish', 'currency_ordering'];
        return $this->formatResponse($this->client->request('POST', '/api/index.php/v1/shop/currencies', $this->pick($args, $fields)));
    }

    private function edit(int $id, array $args): array
    {
        $fields = ['currency_name', 'currency_code', 'currency_code_iso', 'currency_code_num', 'currency_value', 'currency_publish', 'currency_ordering'];
        return $this->formatResponse($this->client->request('PATCH', "/api/index.php/v1/shop/currencies/{$id}", $this->pick($args, $fields)));
    }

    private function delete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/currencies/{$id}");
        return [['type' => 'text', 'text' => "Currency #{$id} deleted successfully."]];
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
        $published = isset($attr['currency_publish']) ? ($attr['currency_publish'] ? 'yes' : 'no') : '?';
        $parts = [
            "ID: {$id}",
            "Name: " . ($attr['currency_name'] ?? '(no name)'),
            "Symbol: " . ($attr['currency_code'] ?? ''),
            "Published: {$published}",
        ];
        if (!empty($attr['currency_code_iso'])) { $parts[] = "ISO: {$attr['currency_code_iso']}"; }
        if (isset($attr['currency_value']))      { $parts[] = "Rate: {$attr['currency_value']}"; }
        if (isset($attr['currency_ordering']))   { $parts[] = "Ordering: {$attr['currency_ordering']}"; }
        return implode("\n", $parts);
    }
}
