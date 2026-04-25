<?php

/**
 * Delete tools for product sub-resources.
 * Sub-resources are added via the product (attrs, attrs2, images, etc.)
 * but can only be deleted through their own endpoints.
 */
class ProductSubResourcesTools
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
                'name'        => 'productsattrs_delete',
                'description' => 'Delete a product attribute combination (variant with price/stock/EAN) by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productsattrs record ID (product_attr_id)']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'productsattr2s_delete',
                'description' => 'Delete a product simple attribute value link (attrs2) by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productsattr2s record ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'productimages_delete',
                'description' => 'Delete an additional product image by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productimages record ID (image_id)']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'productsfreeattrs_delete',
                'description' => 'Delete a product free attribute link by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productsfreeattrs record ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'productsfiles_delete',
                'description' => 'Delete a product downloadable file record by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productsfiles record ID']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'productsvideos_delete',
                'description' => 'Delete a product video link by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productsvideos record ID (video_id)']],
                    'required'   => ['id'],
                ],
            ],
            [
                'name'        => 'productsrelations_delete',
                'description' => 'Delete a related product link by its record ID.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => ['id' => ['type' => 'integer', 'description' => 'productsrelations record ID']],
                    'required'   => ['id'],
                ],
            ],
        ];
    }

    public function call(string $name, array $args): array
    {
        $id = (int) $args['id'];

        $map = [
            'productsattrs_delete'    => '/api/index.php/v1/shop/productsattrs',
            'productsattr2s_delete'   => '/api/index.php/v1/shop/productsattr2s',
            'productimages_delete'    => '/api/index.php/v1/shop/productimages',
            'productsfreeattrs_delete'=> '/api/index.php/v1/shop/productsfreeattrs',
            'productsfiles_delete'    => '/api/index.php/v1/shop/productsfiles',
            'productsvideos_delete'   => '/api/index.php/v1/shop/productsvideos',
            'productsrelations_delete'=> '/api/index.php/v1/shop/productsrelations',
        ];

        if (!isset($map[$name])) {
            throw new RuntimeException("Unknown tool: {$name}");
        }

        $this->client->request('DELETE', "{$map[$name]}/{$id}");

        $label = str_replace(['products', 'product', '_delete'], '', $name);
        return [['type' => 'text', 'text' => ucfirst($label) . " #{$id} deleted successfully."]];
    }
}
