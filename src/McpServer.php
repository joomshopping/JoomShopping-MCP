<?php

class McpServer
{
    /** @var object[] */
    private array $providers;

    private const PROTOCOL_VERSION = '2024-11-05';
    private const SERVER_NAME      = 'joomshopping-mcp';
    private const SERVER_VERSION   = '1.0.0';

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * Start the MCP server: read JSON-RPC messages from STDIN, write responses to STDOUT.
     */
    public function run(): void
    {
        while (($line = fgets(STDIN)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $request = json_decode($line, true);
            if ($request === null) {
                $this->writeError(null, -32700, 'Parse error');
                continue;
            }

            $id     = $request['id'] ?? null;
            $method = $request['method'] ?? '';

            // Notifications have no id and require no response
            if ($id === null && str_starts_with($method, 'notifications/')) {
                continue;
            }

            try {
                $result = $this->dispatch($method, $request['params'] ?? []);
                if ($result !== null) {
                    $this->writeResult($id, $result);
                }
            } catch (Throwable $e) {
                fwrite(STDERR, "Error handling '{$method}': " . $e->getMessage() . "\n");
                $this->writeError($id, -32603, $e->getMessage());
            }
        }
    }

    // -------------------------------------------------------------------------
    // Method dispatcher
    // -------------------------------------------------------------------------

    private function dispatch(string $method, array $params): ?array
    {
        switch ($method) {
            case 'initialize':
                return $this->handleInitialize($params);

            case 'notifications/initialized':
                // Notification — no response needed
                return null;

            case 'tools/list':
                return $this->handleToolsList();

            case 'tools/call':
                return $this->handleToolsCall($params);

            default:
                throw new RuntimeException("Method not found: {$method}");
        }
    }

    // -------------------------------------------------------------------------
    // MCP method handlers
    // -------------------------------------------------------------------------

    private function handleInitialize(array $params): array
    {
        return [
            'protocolVersion' => self::PROTOCOL_VERSION,
            'capabilities'    => [
                'tools' => (object)[],
            ],
            'serverInfo' => [
                'name'    => self::SERVER_NAME,
                'version' => self::SERVER_VERSION,
            ],
        ];
    }

    private function handleToolsList(): array
    {
        $tools = [];
        foreach ($this->providers as $provider) {
            foreach ($provider->getDefinitions() as $def) {
                $tools[] = $def;
            }
        }
        return ['tools' => $tools];
    }

    private function handleToolsCall(array $params): array
    {
        $name      = $params['name'] ?? '';
        $arguments = $params['arguments'] ?? [];

        if (!$name) {
            throw new RuntimeException('tools/call: missing tool name');
        }

        foreach ($this->providers as $provider) {
            foreach ($provider->getDefinitions() as $def) {
                if ($def['name'] === $name) {
                    return ['content' => $provider->call($name, $arguments)];
                }
            }
        }

        throw new RuntimeException("Tool not found: {$name}");
    }

    // -------------------------------------------------------------------------
    // JSON-RPC output helpers
    // -------------------------------------------------------------------------

    private function writeResult(mixed $id, array $result): void
    {
        $this->write([
            'jsonrpc' => '2.0',
            'id'      => $id,
            'result'  => $result,
        ]);
    }

    private function writeError(mixed $id, int $code, string $message): void
    {
        $this->write([
            'jsonrpc' => '2.0',
            'id'      => $id,
            'error'   => [
                'code'    => $code,
                'message' => $message,
            ],
        ]);
    }

    private function write(array $data): void
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        fwrite(STDOUT, $json . "\n");
        fflush(STDOUT);
    }
}
