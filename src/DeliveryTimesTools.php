<?php

class DeliveryTimesTools
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
                'name'        => 'deliverytimes_list',
                'description' => 'Get a list of all delivery times from JoomShopping.',
                'inputSchema' => ['type' => 'object', 'properties' => (object)[], 'required' => []],
            ],
            [
                'name'        => 'deliverytimes_get',
                'description' => 'Get a single delivery time by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Delivery time ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'deliverytimes_add',
                'description' => 'Add a new delivery time to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name' => ['type' => 'string',  'description' => 'Label, e.g. "1-3 days" (required)'],
                        'days' => ['type' => 'integer', 'description' => 'Number of days'],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'deliverytimes_edit',
                'description' => 'Edit an existing delivery time in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id'   => ['type' => 'integer', 'description' => 'Delivery time ID (required)'],
                        'name' => ['type' => 'string',  'description' => 'New label'],
                        'days' => ['type' => 'integer', 'description' => 'Number of days'],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'deliverytimes_delete',
                'description' => 'Delete a delivery time by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'Delivery time ID']],
                    'required'   => ['id'],
                ],
            ],
        ];
    }

    public function call(string $name, array $args): array
    {
        switch ($name) {
            case 'deliverytimes_list':
                return $this->list();
            case 'deliverytimes_get':
                return $this->get((int) $args['id']);
            case 'deliverytimes_add':
                return $this->add($args);
            case 'deliverytimes_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'deliverytimes_delete':
                return $this->delete((int) $args['id']);
            default:
                throw new RuntimeException("Unknown tool: {$name}");
        }
    }

    private function list(): array
    {
        return $this->formatResponse($this->client->request('GET', '/api/index.php/v1/shop/deliverytimes'));
    }

    private function get(int $id): array
    {
        return $this->formatResponse($this->client->request('GET', "/api/index.php/v1/shop/deliverytimes/{$id}"));
    }

    private function add(array $args): array
    {
        $body = $this->pick($args, ['name', 'days']);
        return $this->formatResponse($this->client->request('POST', '/api/index.php/v1/shop/deliverytimes', $body));
    }

    private function edit(int $id, array $args): array
    {
        $body = $this->pick($args, ['name', 'days']);
        return $this->formatResponse($this->client->request('PATCH', "/api/index.php/v1/shop/deliverytimes/{$id}", $body));
    }

    private function delete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/deliverytimes/{$id}");
        return [['type' => 'text', 'text' => "Delivery time #{$id} deleted successfully."]];
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
        if (isset($attr['days'])) { $parts[] = "Days: {$attr['days']}"; }
        return implode("\n", $parts);
    }
}
