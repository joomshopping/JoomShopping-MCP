<?php

class ProductsTools
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
                'name'        => 'products_list',
                'description' => 'Get a list of products from JoomShopping. Supports filtering by category, sorting, and pagination.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'filter_category' => [
                            'type'        => 'integer',
                            'description' => 'Filter by category ID',
                        ],
                        'ordering' => [
                            'type'        => 'string',
                            'description' => 'Sort field: product_id, product_price, product_date_added, hits, average_rating',
                            'enum'        => ['product_id', 'product_price', 'product_date_added', 'hits', 'average_rating'],
                        ],
                        'direction' => [
                            'type'        => 'string',
                            'description' => 'Sort direction: ASC or DESC',
                            'enum'        => ['ASC', 'DESC'],
                        ],
                        'display' => [
                            'type'        => 'string',
                            'description' => 'Field set: omit for full, "short" for minimal set',
                            'enum'        => ['short'],
                        ],
                        'limit' => [
                            'type'        => 'integer',
                            'description' => 'Items per page',
                        ],
                        'offset' => [
                            'type'        => 'integer',
                            'description' => 'Pagination offset',
                        ],
                    ],
                    'required' => [],
                ],
            ],
            [
                'name'        => 'products_get',
                'description' => 'Get a single product by ID from JoomShopping (includes images, attributes, files, options, prices, relations, videos).',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Product ID',
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'products_add',
                'description' => 'Add a new product to JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'name' => [
                            'type'        => 'string',
                            'description' => 'Product name (required)',
                        ],
                        'alias' => [
                            'type'        => 'string',
                            'description' => 'Alias (slug)',
                        ],
                        'short_description' => [
                            'type'        => 'string',
                            'description' => 'Short description',
                        ],
                        'description' => [
                            'type'        => 'string',
                            'description' => 'Full description',
                        ],
                        'product_price' => [
                            'type'        => 'number',
                            'description' => 'Price',
                        ],
                        'product_old_price' => [
                            'type'        => 'number',
                            'description' => 'Old (crossed-out) price',
                        ],
                        'product_buy_price' => [
                            'type'        => 'number',
                            'description' => 'Purchase (buy) price',
                        ],
                        'product_quantity' => [
                            'type'        => 'number',
                            'description' => 'Stock quantity',
                        ],
                        'unlimited' => [
                            'type'        => 'integer',
                            'description' => 'Unlimited stock: 1 or 0',
                            'enum'        => [0, 1],
                        ],
                        'product_publish' => [
                            'type'        => 'integer',
                            'description' => 'Published: 1 or 0',
                            'enum'        => [0, 1],
                        ],
                        'main_category_id' => [
                            'type'        => 'integer',
                            'description' => 'Main category ID',
                        ],
                        'categories' => [
                            'type'        => 'array',
                            'description' => 'Additional category IDs',
                            'items'       => ['type' => 'integer'],
                        ],
                        'product_manufacturer_id' => [
                            'type'        => 'integer',
                            'description' => 'Manufacturer ID',
                        ],
                        'product_tax_id' => [
                            'type'        => 'integer',
                            'description' => 'Tax ID',
                        ],
                        'product_ean' => [
                            'type'        => 'string',
                            'description' => 'EAN / barcode',
                        ],
                        'manufacturer_code' => [
                            'type'        => 'string',
                            'description' => 'Manufacturer article number',
                        ],
                        'product_weight' => [
                            'type'        => 'number',
                            'description' => 'Weight',
                        ],
                        'product_availability' => [
                            'type'        => 'string',
                            'description' => 'Availability code',
                        ],
                        'label_id' => [
                            'type'        => 'integer',
                            'description' => 'Label ID',
                        ],
                        'vendor_id' => [
                            'type'        => 'integer',
                            'description' => 'Vendor ID',
                        ],
                        'delivery_times_id' => [
                            'type'        => 'integer',
                            'description' => 'Delivery time ID',
                        ],
                        'currency_id' => [
                            'type'        => 'integer',
                            'description' => 'Currency ID',
                        ],
                        'ordering' => [
                            'type'        => 'integer',
                            'description' => 'Sort order',
                        ],
                        'meta_title' => [
                            'type'        => 'string',
                            'description' => 'Meta title',
                        ],
                        'meta_description' => [
                            'type'        => 'string',
                            'description' => 'Meta description',
                        ],
                        'meta_keyword' => [
                            'type'        => 'string',
                            'description' => 'Meta keywords',
                        ],
                        'product_url' => [
                            'type'        => 'string',
                            'description' => 'External product URL',
                        ],
                        'images' => [
                            'type'        => 'array',
                            'description' => 'Product images. Each element MUST be an object (not a string). Use "image.url" to download from URL, or "image_name" for an existing file on server. Example: [{"image.url": "https://example.com/photo.jpg", "name": "Front view"}, {"image_name": "existing.jpg"}]',
                            'items'       => [
                                'type'       => 'object',
                                'properties' => [
                                    'image.url'  => ['type' => 'string', 'description' => 'URL to download image from'],
                                    'image_name' => ['type' => 'string', 'description' => 'Existing filename on server'],
                                    'name'       => ['type' => 'string', 'description' => 'Alt text'],
                                    'title'      => ['type' => 'string'],
                                    'ordering'   => ['type' => 'integer'],
                                ],
                            ],
                        ],
                        'attrs' => [
                            'type'        => 'array',
                            'description' => 'Attribute combinations (independent=0): each variant has its own price, stock, EAN, dimensions. Each item: {"price": 90.00, "count": 1, "ean": "A1", "weight": 0.5, "attrs": {"attr_id": value_id, ...}}',
                            'items'       => [
                                'type'       => 'object',
                                'properties' => [
                                    'attrs'                  => ['type' => 'object',  'description' => 'Attribute values map: {"attr_id": value_id, ...}'],
                                    'price'                  => ['type' => 'number',  'description' => 'Sell price'],
                                    'buy_price'              => ['type' => 'number',  'description' => 'Purchase price'],
                                    'old_price'              => ['type' => 'number',  'description' => 'Old price'],
                                    'count'                  => ['type' => 'number',  'description' => 'Stock count'],
                                    'ean'                    => ['type' => 'string',  'description' => 'EAN / SKU'],
                                    'manufacturer_code'      => ['type' => 'string',  'description' => 'Manufacturer code'],
                                    'real_ean'               => ['type' => 'string',  'description' => 'Real EAN'],
                                    'weight'                 => ['type' => 'number',  'description' => 'Weight'],
                                    'weight_volume_units'    => ['type' => 'number',  'description' => 'Volumetric weight'],
                                    'ext_attribute_product_id' => ['type' => 'integer', 'description' => 'External attribute product ID'],
                                ],
                            ],
                        ],
                        'attrs2' => [
                            'type'        => 'array',
                            'description' => 'Simple attribute list (independent=1): adds a selectable value with optional price modifier. Each item: {"attr_id": 1, "attr_value_id": 1, "price_mod": "+", "addprice": 20}',
                            'items'       => [
                                'type'       => 'object',
                                'properties' => [
                                    'attr_id'       => ['type' => 'integer', 'description' => 'Attribute ID'],
                                    'attr_value_id' => ['type' => 'integer', 'description' => 'Attribute value ID'],
                                    'price_mod'     => ['type' => 'string',  'description' => 'Price modifier sign: "+" or "-"', 'enum' => ['+', '-']],
                                    'addprice'      => ['type' => 'number',  'description' => 'Additional price amount'],
                                ],
                            ],
                        ],
                        'freeattrs' => [
                            'type'        => 'array',
                            'description' => 'Free attribute links. Each item: {"attr_id": "1"}',
                            'items'       => ['type' => 'object'],
                        ],
                        'files' => [
                            'type'        => 'array',
                            'description' => 'Downloadable files. Each item: {"demo": "1.mp3", "demo_descr": "...", "file": "1.zip", "file_descr": "..."}',
                            'items'       => ['type' => 'object'],
                        ],
                        'videos' => [
                            'type'        => 'array',
                            'description' => 'Videos. Each item: {"video_name": "1.mp4"} or {"video_code": "<embed...>", "video_preview": "preview.jpg"}',
                            'items'       => ['type' => 'object'],
                        ],
                        'relations' => [
                            'type'        => 'array',
                            'description' => 'Related products. Each item: {"product_related_id": 5}',
                            'items'       => ['type' => 'object'],
                        ],
                        'fields' => [
                            'type'        => 'object',
                            'description' => 'Product characteristics (extra fields). Object where keys are field IDs and values are fieldvalue IDs. Example: {"1": "4", "2": "165", "4": 0}. Use characteristics_fields_list to get field IDs and characteristics_fieldvalues_list to get value IDs.',
                        ],
                    ],
                    'required' => ['name'],
                ],
            ],
            [
                'name'        => 'products_edit',
                'description' => 'Edit an existing product in JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Product ID (required)',
                        ],
                        'name' => [
                            'type'        => 'string',
                            'description' => 'New name',
                        ],
                        'alias' => [
                            'type'        => 'string',
                            'description' => 'New alias (slug)',
                        ],
                        'short_description' => [
                            'type'        => 'string',
                            'description' => 'New short description',
                        ],
                        'description' => [
                            'type'        => 'string',
                            'description' => 'New full description',
                        ],
                        'product_price' => [
                            'type'        => 'number',
                            'description' => 'New price',
                        ],
                        'product_old_price' => [
                            'type'        => 'number',
                            'description' => 'New old (crossed-out) price',
                        ],
                        'product_buy_price' => [
                            'type'        => 'number',
                            'description' => 'New purchase price',
                        ],
                        'product_quantity' => [
                            'type'        => 'number',
                            'description' => 'New stock quantity',
                        ],
                        'unlimited' => [
                            'type'        => 'integer',
                            'description' => 'Unlimited stock: 1 or 0',
                            'enum'        => [0, 1],
                        ],
                        'product_publish' => [
                            'type'        => 'integer',
                            'description' => 'Published: 1 or 0',
                            'enum'        => [0, 1],
                        ],
                        'main_category_id' => [
                            'type'        => 'integer',
                            'description' => 'New main category ID',
                        ],
                        'categories' => [
                            'type'        => 'array',
                            'description' => 'New additional category IDs',
                            'items'       => ['type' => 'integer'],
                        ],
                        'product_manufacturer_id' => [
                            'type'        => 'integer',
                            'description' => 'New manufacturer ID',
                        ],
                        'product_tax_id' => [
                            'type'        => 'integer',
                            'description' => 'New tax ID',
                        ],
                        'product_ean' => [
                            'type'        => 'string',
                            'description' => 'New EAN / barcode',
                        ],
                        'manufacturer_code' => [
                            'type'        => 'string',
                            'description' => 'New manufacturer article number',
                        ],
                        'product_weight' => [
                            'type'        => 'number',
                            'description' => 'New weight',
                        ],
                        'product_availability' => [
                            'type'        => 'string',
                            'description' => 'New availability code',
                        ],
                        'label_id' => [
                            'type'        => 'integer',
                            'description' => 'New label ID',
                        ],
                        'vendor_id' => [
                            'type'        => 'integer',
                            'description' => 'New vendor ID',
                        ],
                        'delivery_times_id' => [
                            'type'        => 'integer',
                            'description' => 'New delivery time ID',
                        ],
                        'currency_id' => [
                            'type'        => 'integer',
                            'description' => 'New currency ID',
                        ],
                        'ordering' => [
                            'type'        => 'integer',
                            'description' => 'Sort order',
                        ],
                        'meta_title' => [
                            'type'        => 'string',
                            'description' => 'New meta title',
                        ],
                        'meta_description' => [
                            'type'        => 'string',
                            'description' => 'New meta description',
                        ],
                        'meta_keyword' => [
                            'type'        => 'string',
                            'description' => 'New meta keywords',
                        ],
                        'product_url' => [
                            'type'        => 'string',
                            'description' => 'New external product URL',
                        ],
                        'images' => [
                            'type'        => 'array',
                            'description' => 'Product images. Each element MUST be an object (not a string). Use "image.url" to download from URL, or "image_name" for an existing file on server. Example: [{"image.url": "https://example.com/photo.jpg", "name": "Front view"}, {"image_name": "existing.jpg"}]',
                            'items'       => [
                                'type'       => 'object',
                                'properties' => [
                                    'image.url'  => ['type' => 'string', 'description' => 'URL to download image from'],
                                    'image_name' => ['type' => 'string', 'description' => 'Existing filename on server'],
                                    'name'       => ['type' => 'string', 'description' => 'Alt text'],
                                    'title'      => ['type' => 'string'],
                                    'ordering'   => ['type' => 'integer'],
                                ],
                            ],
                        ],
                        'attrs' => [
                            'type'        => 'array',
                            'description' => 'Attribute combinations (independent=0): each variant has its own price, stock, EAN, dimensions. Each item: {"price": 90.00, "count": 1, "ean": "A1", "weight": 0.5, "attrs": {"attr_id": value_id, ...}}',
                            'items'       => [
                                'type'       => 'object',
                                'properties' => [
                                    'attrs'                  => ['type' => 'object',  'description' => 'Attribute values map: {"attr_id": value_id, ...}'],
                                    'price'                  => ['type' => 'number',  'description' => 'Sell price'],
                                    'buy_price'              => ['type' => 'number',  'description' => 'Purchase price'],
                                    'old_price'              => ['type' => 'number',  'description' => 'Old price'],
                                    'count'                  => ['type' => 'number',  'description' => 'Stock count'],
                                    'ean'                    => ['type' => 'string',  'description' => 'EAN / SKU'],
                                    'manufacturer_code'      => ['type' => 'string',  'description' => 'Manufacturer code'],
                                    'real_ean'               => ['type' => 'string',  'description' => 'Real EAN'],
                                    'weight'                 => ['type' => 'number',  'description' => 'Weight'],
                                    'weight_volume_units'    => ['type' => 'number',  'description' => 'Volumetric weight'],
                                    'ext_attribute_product_id' => ['type' => 'integer', 'description' => 'External attribute product ID'],
                                ],
                            ],
                        ],
                        'attrs2' => [
                            'type'        => 'array',
                            'description' => 'Simple attribute list (independent=1): adds a selectable value with optional price modifier. Each item: {"attr_id": 1, "attr_value_id": 1, "price_mod": "+", "addprice": 20}',
                            'items'       => [
                                'type'       => 'object',
                                'properties' => [
                                    'attr_id'       => ['type' => 'integer', 'description' => 'Attribute ID'],
                                    'attr_value_id' => ['type' => 'integer', 'description' => 'Attribute value ID'],
                                    'price_mod'     => ['type' => 'string',  'description' => 'Price modifier sign: "+" or "-"', 'enum' => ['+', '-']],
                                    'addprice'      => ['type' => 'number',  'description' => 'Additional price amount'],
                                ],
                            ],
                        ],
                        'freeattrs' => [
                            'type'        => 'array',
                            'description' => 'Free attribute links. Each item: {"attr_id": "1"}',
                            'items'       => ['type' => 'object'],
                        ],
                        'files' => [
                            'type'        => 'array',
                            'description' => 'Downloadable files. Each item: {"demo": "1.mp3", "demo_descr": "...", "file": "1.zip", "file_descr": "..."}',
                            'items'       => ['type' => 'object'],
                        ],
                        'videos' => [
                            'type'        => 'array',
                            'description' => 'Videos. Each item: {"video_name": "1.mp4"} or {"video_code": "<embed...>", "video_preview": "preview.jpg"}',
                            'items'       => ['type' => 'object'],
                        ],
                        'relations' => [
                            'type'        => 'array',
                            'description' => 'Related products. Each item: {"product_related_id": 5}',
                            'items'       => ['type' => 'object'],
                        ],
                        'fields' => [
                            'type'        => 'object',
                            'description' => 'Product characteristics (extra fields). Object where keys are field IDs and values are fieldvalue IDs. Example: {"1": "4", "2": "165", "4": 0}. Use characteristics_fields_list to get field IDs and characteristics_fieldvalues_list to get value IDs.',
                        ],
                    ],
                    'required' => ['id'],
                ],
            ],
            [
                'name'        => 'products_delete',
                'description' => 'Delete a product by ID from JoomShopping.',
                'inputSchema' => [
                    'type'       => 'object',
                    'properties' => [
                        'id' => [
                            'type'        => 'integer',
                            'description' => 'Product ID',
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
            case 'products_list':
                return $this->list($args);
            case 'products_get':
                return $this->get((int) $args['id']);
            case 'products_add':
                return $this->add($args);
            case 'products_edit':
                $id = (int) $args['id'];
                unset($args['id']);
                return $this->edit($id, $args);
            case 'products_delete':
                return $this->delete((int) $args['id']);
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

        if (isset($args['filter_category'])) {
            $query['filter[category]'] = $args['filter_category'];
        }
        if (isset($args['ordering'])) {
            $query['list[ordering]'] = $args['ordering'];
        }
        if (isset($args['direction'])) {
            $query['list[direction]'] = $args['direction'];
        }
        if (isset($args['display'])) {
            $query['list[display]'] = $args['display'];
        }
        if (isset($args['limit'])) {
            $query['page[limit]'] = $args['limit'];
        }
        if (isset($args['offset'])) {
            $query['page[offset]'] = $args['offset'];
        }

        $url = '/api/index.php/v1/shop/products';
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        $data = $this->client->request('GET', $url);
        return $this->formatResponse($data);
    }

    private function get(int $id): array
    {
        $data = $this->client->request('GET', "/api/index.php/v1/shop/products/{$id}");
        return $this->formatResponse($data, true);
    }

    private function add(array $args): array
    {
        $body = $this->buildBody($args);
        $data = $this->client->request('POST', '/api/index.php/v1/shop/products', $body);
        return $this->formatResponse($data);
    }

    private function edit(int $id, array $args): array
    {
        $body = $this->buildBody($args);
        $data = $this->client->request('PATCH', "/api/index.php/v1/shop/products/{$id}", $body);
        return $this->formatResponse($data);
    }

    private function delete(int $id): array
    {
        $this->client->request('DELETE', "/api/index.php/v1/shop/products/{$id}");
        return [
            [
                'type' => 'text',
                'text' => "Product #{$id} deleted successfully.",
            ],
        ];
    }

    private function buildBody(array $args): array
    {
        $fields = [
            'name', 'alias', 'short_description', 'description',
            'product_price', 'product_old_price', 'product_buy_price',
            'product_quantity', 'unlimited', 'product_publish',
            'main_category_id', 'categories',
            'product_manufacturer_id', 'product_tax_id',
            'product_ean', 'manufacturer_code', 'real_ean',
            'product_weight', 'weight_volume_units',
            'product_availability', 'label_id', 'vendor_id',
            'delivery_times_id', 'currency_id', 'ordering',
            'meta_title', 'meta_description', 'meta_keyword',
            'product_url', 'product_template',
            // sub-resources
            'images', 'attrs', 'attrs2', 'freeattrs', 'files', 'videos', 'relations', 'fields',
        ];

        $body = [];
        foreach ($fields as $field) {
            if (isset($args[$field])) {
                $body[$field] = $args[$field];
            }
        }

        // Ensure main_category_id is set when categories are provided
        if (!empty($body['categories']) && !isset($body['main_category_id'])) {
            $body['main_category_id'] = (int) $body['categories'][0];
        }

        // Ensure categories contains main_category_id
        if (isset($body['main_category_id']) && empty($body['categories'])) {
            $body['categories'] = [$body['main_category_id']];
        }

        return $body;
    }

    // -------------------------------------------------------------------------
    // Response formatting
    // -------------------------------------------------------------------------

    private function formatResponse(array $data, bool $detailed = false): array
    {
        if (isset($data['data']) && is_array($data['data']) && isset($data['data'][0])) {
            $lines = [];
            foreach ($data['data'] as $item) {
                $lines[] = $this->formatItem($item, false);
            }
            return [
                [
                    'type' => 'text',
                    'text' => implode("\n\n", $lines),
                ],
            ];
        }

        if (isset($data['data'])) {
            return [
                [
                    'type' => 'text',
                    'text' => $this->formatItem($data['data'], $detailed),
                ],
            ];
        }

        return [
            [
                'type' => 'text',
                'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ],
        ];
    }

    private function formatItem(array $item, bool $detailed): string
    {
        $id   = $item['id'] ?? '?';
        $attr = $item['attributes'] ?? $item;

        $name        = $attr['name'] ?? '(no name)';
        $alias       = $attr['alias'] ?? '';
        $published   = isset($attr['product_publish']) ? ($attr['product_publish'] ? 'yes' : 'no') : '?';
        $price       = isset($attr['product_price']) ? (float) $attr['product_price'] : null;
        $old_price   = isset($attr['product_old_price']) ? (float) $attr['product_old_price'] : null;
        $buy_price   = isset($attr['product_buy_price']) ? (float) $attr['product_buy_price'] : null;
        $min_price   = isset($attr['min_price']) ? (float) $attr['min_price'] : null;
        $quantity    = $attr['product_quantity'] ?? null;
        $unlimited   = (int) ($attr['unlimited'] ?? 0);
        $category    = $attr['main_category_id'] ?? '';
        $manufacturer = $attr['product_manufacturer_id'] ?? '';
        $ean         = $attr['product_ean'] ?? '';
        $art         = $attr['manufacturer_code'] ?? '';
        $real_ean    = $attr['real_ean'] ?? '';
        $weight      = isset($attr['product_weight']) ? (float) $attr['product_weight'] : null;
        $image       = $attr['image'] ?? '';
        $ordering    = $attr['ordering'] ?? '';
        $availability = $attr['product_availability'] ?? '';
        $tax_id      = $attr['product_tax_id'] ?? '';
        $currency_id = $attr['currency_id'] ?? '';
        $label_id    = $attr['label_id'] ?? 0;
        $vendor_id   = $attr['vendor_id'] ?? 0;
        $delivery_id = $attr['delivery_times_id'] ?? 0;
        $hits        = $attr['hits'] ?? '';
        $rating      = $attr['average_rating'] ?? '';
        $reviews     = $attr['reviews_count'] ?? '';
        $date_added  = $attr['product_date_added'] ?? '';
        $date_modify = $attr['date_modify'] ?? '';
        $short_desc  = $attr['short_description'] ?? '';
        $product_url = $attr['product_url'] ?? '';
        $meta_title  = $attr['meta_title'] ?? '';
        $meta_desc   = $attr['meta_description'] ?? '';
        $meta_kw     = $attr['meta_keyword'] ?? '';
        $diff_prices = $attr['different_prices'] ?? 0;
        $parent_id   = $attr['parent_id'] ?? 0;

        $parts = [
            "ID: {$id}",
            "Name: {$name}",
            "Published: {$published}",
        ];

        if ($parent_id)          { $parts[] = "Parent ID: {$parent_id}"; }
        if ($alias)              { $parts[] = "Alias: {$alias}"; }
        if ($short_desc)         { $parts[] = "Short description: {$short_desc}"; }

        // Pricing block
        if ($price !== null)     { $parts[] = "Price: {$price}"; }
        if ($old_price)          { $parts[] = "Old price: {$old_price}"; }
        if ($buy_price)          { $parts[] = "Buy price: {$buy_price}"; }
        if ($min_price !== null && $min_price != $price) {
                                   $parts[] = "Min price: {$min_price}"; }
        if ($diff_prices)        { $parts[] = "Has variant prices: yes"; }

        // Stock
        if ($unlimited) {
            $parts[] = "Quantity: unlimited";
        } elseif ($quantity !== null) {
            $parts[] = "Quantity: {$quantity}";
        }
        if ($availability)       { $parts[] = "Availability: {$availability}"; }

        // IDs
        if ($category !== '')    { $parts[] = "Main category ID: {$category}"; }
        if ($manufacturer)       { $parts[] = "Manufacturer ID: {$manufacturer}"; }
        if ($tax_id)             { $parts[] = "Tax ID: {$tax_id}"; }
        if ($currency_id)        { $parts[] = "Currency ID: {$currency_id}"; }
        if ($label_id)           { $parts[] = "Label ID: {$label_id}"; }
        if ($vendor_id)          { $parts[] = "Vendor ID: {$vendor_id}"; }
        if ($delivery_id)        { $parts[] = "Delivery time ID: {$delivery_id}"; }

        // Identifiers
        if ($ean)                { $parts[] = "EAN: {$ean}"; }
        if ($art)                { $parts[] = "Article: {$art}"; }
        if ($real_ean)           { $parts[] = "Real EAN: {$real_ean}"; }

        // Physical
        if ($weight)             { $parts[] = "Weight: {$weight}"; }

        // Media / stats
        if ($image)              { $parts[] = "Image: {$image}"; }
        if ($hits !== '')        { $parts[] = "Hits: {$hits}"; }
        if ($rating)             { $parts[] = "Rating: {$rating} ({$reviews} reviews)"; }

        // Dates
        if ($date_added)         { $parts[] = "Date added: {$date_added}"; }
        if ($date_modify)        { $parts[] = "Date modified: {$date_modify}"; }

        // SEO
        if ($meta_title)         { $parts[] = "Meta title: {$meta_title}"; }
        if ($meta_desc)          { $parts[] = "Meta description: {$meta_desc}"; }
        if ($meta_kw)            { $parts[] = "Meta keywords: {$meta_kw}"; }

        if ($product_url)        { $parts[] = "Product URL: {$product_url}"; }
        if ($ordering !== '')    { $parts[] = "Ordering: {$ordering}"; }

        if ($detailed) {
            $this->appendSubResources($attr, $parts);
        }

        return implode("\n", $parts);
    }

    private function appendSubResources(array $attr, array &$parts): void
    {
        if (!empty($attr['categories'])) {
            $parts[] = "Categories: " . implode(', ', (array) $attr['categories']);
        }

        if (!empty($attr['images'])) {
            $lines = [];
            foreach ($attr['images'] as $img) {
                $line = "  [{$img['id']}] {$img['image_name']}";
                if ($img['name'] ?? '') { $line .= " alt=\"{$img['name']}\""; }
                $lines[] = $line;
            }
            $parts[] = "Images:\n" . implode("\n", $lines);
        }

        if (!empty($attr['attrs'])) {
            $lines = [];
            foreach ($attr['attrs'] as $a) {
                $line = "  [{$a['id']}] price={$a['price']} count={$a['count']}";
                if ($a['ean'] ?? '') { $line .= " ean={$a['ean']}"; }
                if ($a['weight'] ?? 0) { $line .= " weight={$a['weight']}"; }
                $lines[] = $line;
            }
            $parts[] = "Attribute combinations (attrs):\n" . implode("\n", $lines);
        }

        if (!empty($attr['attr2s'])) {
            $lines = [];
            foreach ($attr['attr2s'] as $a) {
                $line = "  [{$a['id']}] attr_id={$a['attr_id']} value_id={$a['attr_value_id']}";
                if ((float) ($a['addprice'] ?? 0)) {
                    $line .= " addprice={$a['price_mod']}{$a['addprice']}";
                }
                $lines[] = $line;
            }
            $parts[] = "Attribute values (attr2s):\n" . implode("\n", $lines);
        }

        if (!empty($attr['freeattrs'])) {
            $ids = array_map(fn($a) => "attr_id={$a['attr_id']}", $attr['freeattrs']);
            $parts[] = "Free attributes: " . implode(', ', $ids);
        }

        if (!empty($attr['options'])) {
            $lines = [];
            foreach ($attr['options'] as $o) {
                $lines[] = "  {$o['key']}={$o['value']}";
            }
            $parts[] = "Options:\n" . implode("\n", $lines);
        }

        if (!empty($attr['prices'])) {
            $lines = [];
            foreach ($attr['prices'] as $p) {
                $lines[] = "  discount={$p['discount']} qty={$p['product_quantity_start']}-{$p['product_quantity_finish']}";
            }
            $parts[] = "Group prices:\n" . implode("\n", $lines);
        }

        if (!empty($attr['relations'])) {
            $ids = array_map(fn($r) => $r['product_related_id'] ?? '?', $attr['relations']);
            $parts[] = "Related products: " . implode(', ', $ids);
        }

        if (!empty($attr['videos'])) {
            $lines = [];
            foreach ($attr['videos'] as $v) {
                $lines[] = "  [{$v['id']}] " . ($v['video_name'] ?: $v['video_code'] ?? '?');
            }
            $parts[] = "Videos:\n" . implode("\n", $lines);
        }

        if (!empty($attr['files'])) {
            $lines = [];
            foreach ($attr['files'] as $f) {
                $line = "  [{$f['id']}] {$f['file']}";
                if ($f['file_descr'] ?? '') { $line .= " — {$f['file_descr']}"; }
                $lines[] = $line;
            }
            $parts[] = "Files:\n" . implode("\n", $lines);
        }

        if (!empty($attr['fields']) && is_array($attr['fields'])) {
            $lines = [];
            foreach ($attr['fields'] as $fieldId => $valueId) {
                $lines[] = "  field_id={$fieldId} → value_id={$valueId}";
            }
            $parts[] = "Characteristics (fields):\n" . implode("\n", $lines);
        }
    }
}
