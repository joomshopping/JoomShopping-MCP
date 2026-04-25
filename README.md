# JoomShopping MCP — Tool Reference

Complete list of tools exposed by this MCP server.
Each tool maps to a JoomShopping Web Service REST endpoint.

---

## Categories

| Tool | Description |
|------|-------------|
| `categories_list` | List all categories |
| `categories_get` | Get category by ID |
| `categories_add` | Create a category |
| `categories_edit` | Update a category |
| `categories_delete` | Delete a category |

**Key fields:** `name`\*, `alias`, `category_parent_id`, `category_publish`, `ordering`, `category_image` / `image.url`, `img_alt`, `img_title`, `short_description`, `description`, `meta_title`, `meta_description`, `meta_keyword`, `products_page`, `products_row`, `product_sorting`, `product_sorting_direction`

---

## Manufacturers

| Tool | Description |
|------|-------------|
| `manufacturers_list` | List all manufacturers |
| `manufacturers_get` | Get manufacturer by ID |
| `manufacturers_add` | Create a manufacturer |
| `manufacturers_edit` | Update a manufacturer |
| `manufacturers_delete` | Delete a manufacturer |

---

## Products

| Tool | Description |
|------|-------------|
| `products_list` | List products (filter by category, sorting, pagination) |
| `products_get` | Get product by ID — includes images, attrs, files, options, prices, videos, relations |
| `products_add` | Create a product with all sub-resources in one request |
| `products_edit` | Update a product |
| `products_delete` | Delete a product |

**Key fields:** `name`\*, `alias`, `description`, `short_description`, `product_price`, `product_old_price`, `product_buy_price`, `product_quantity`, `unlimited`, `product_ean`, `manufacturer_code`, `product_publish`, `product_manufacturer_id`, `main_category_id`, `categories`, `product_tax_id`, `currency_id`, `delivery_times_id`, `label_id`, `product_weight`, `meta_title`, `meta_description`, `meta_keyword`

**Sub-resources (pass as arrays in `products_add` / `products_edit`):**

| Field | Description |
|-------|-------------|
| `images` | Additional images — `image.url`, `image.base64`, or `image_name` |
| `attrs` | Attribute combinations (`independent=0`) — each item has `price`, `count`, `ean`, `weight`, `attrs: {"attr_id": value_id}` |
| `attrs2` | Simple attribute list (`independent=1`) — `attr_id`, `attr_value_id`, `price_mod` (`+`/`-`), `addprice` |
| `freeattrs` | Free attribute links — `{"attr_id": N}` |
| `files` | Downloadable files — `file`, `file.url`, `file.base64`, `demo`, `file_descr` |
| `videos` | Video links — `video_name`, `video_code` |
| `relations` | Related products — `{"product_related_id": N}` |

**`products_list` filters:** `filter[category_id]`, `filter[manufacturer_id]`, `filter[publish]`, `sort`, `sort_direction` (`asc`/`desc`), `limit`, `offset`

---

## Product sub-resource delete

Sub-resources are created through the product. To delete an individual record use the dedicated tool with its own `id`.

| Tool | Deletes |
|------|---------|
| `productsattrs_delete` | Attribute combination variant (`productsattrs.product_attr_id`) |
| `productsattr2s_delete` | Simple attribute value link (`productsattr2s.id`) |
| `productimages_delete` | Additional image (`productimages.image_id`) |
| `productsfreeattrs_delete` | Free attribute link (`productsfreeattrs.id`) |
| `productsfiles_delete` | Downloadable file record (`productsfiles.id`) |
| `productsvideos_delete` | Video link (`productsvideos.video_id`) |
| `productsrelations_delete` | Related product link (`productsrelations.id`) |

---

## Attributes

### Attribute definitions

| Tool | Description |
|------|-------------|
| `attributes_list` | List all attributes |
| `attributes_get` | Get attribute by ID |
| `attributes_add` | Create an attribute |
| `attributes_edit` | Update an attribute |
| `attributes_delete` | Delete an attribute |

**Key fields:** `name`\*, `attr_type`, `attr_ordering`, `publish`, `group`, `required`, `allcats`, `cats`

**`independent` values:**
- `0` — attribute for product variants (each variant gets its own price, stock, EAN, dimensions)
- `1` — simple list (value shown with optional price modifier)

### Attribute values

| Tool | Description |
|------|-------------|
| `attributesvalues_list` | List all attribute values |
| `attributesvalues_get` | Get attribute value by ID |
| `attributesvalues_add` | Create an attribute value |
| `attributesvalues_edit` | Update an attribute value |
| `attributesvalues_delete` | Delete an attribute value |

**Key fields:** `name`\*, `attr_id`\*, `publish`, `value_ordering`, `image` / `image.base64` / `image.url`

---

## Characteristics (product fields)

Characteristics are structured product specifications (e.g. "Doors: 4", "Tire Width: 165 mm").

### Field definitions

| Tool | Description |
|------|-------------|
| `characteristics_fields_list` | List all characteristic field definitions |
| `characteristics_fields_get` | Get field definition by ID |
| `characteristics_fields_add` | Create a field definition |
| `characteristics_fields_edit` | Update a field definition |
| `characteristics_fields_delete` | Delete a field definition |

### Predefined values

| Tool | Description |
|------|-------------|
| `characteristics_fieldvalues_list` | List predefined values (optionally filter by `field_id`) |
| `characteristics_fieldvalues_get` | Get value by ID |
| `characteristics_fieldvalues_add` | Create a predefined value for a field |
| `characteristics_fieldvalues_edit` | Update a predefined value |
| `characteristics_fieldvalues_delete` | Delete a predefined value |

### Assign to product

| Tool | Description |
|------|-------------|
| `characteristics_product_get` | Get all characteristic values of a product |
| `characteristics_product_set` | Set (POST) characteristics for a product — `{"field_id": value_id, ...}` |
| `characteristics_product_update` | Update (PATCH) characteristics — only provided fields are changed |

---

## Labels (product badges)

| Tool | Description |
|------|-------------|
| `labels_list` | List all labels |
| `labels_get` | Get label by ID |
| `labels_add` | Create a label |
| `labels_edit` | Update a label |
| `labels_delete` | Delete a label |

**Key fields:** `name`\*, `image` / `image.base64` / `image.url`

Assign a label to a product via `products_add` / `products_edit` using `label_id`.

---

## Currencies

| Tool | Description |
|------|-------------|
| `currencies_list` | List all currencies |
| `currencies_get` | Get currency by ID |
| `currencies_add` | Create a currency |
| `currencies_edit` | Update a currency |
| `currencies_delete` | Delete a currency |

**Key fields:** `currency_name`\*, `currency_code`\* (symbol), `currency_code_iso`, `currency_value` (exchange rate), `currency_publish`, `currency_ordering`

---

## Delivery times

| Tool | Description |
|------|-------------|
| `deliverytimes_list` | List all delivery times |
| `deliverytimes_get` | Get delivery time by ID |
| `deliverytimes_add` | Create a delivery time |
| `deliverytimes_edit` | Update a delivery time |
| `deliverytimes_delete` | Delete a delivery time |

**Key fields:** `name`\* (e.g. `"1-3 days"`), `days`

Assign to a product via `delivery_times_id`.

---

## Taxes

| Tool | Description |
|------|-------------|
| `taxes_list` | List all taxes |
| `taxes_get` | Get tax by ID |
| `taxes_add` | Create a tax |
| `taxes_edit` | Update a tax |
| `taxes_delete` | Delete a tax |

**Key fields:** `tax_name`\*, `tax_value`\* (rate in %), `ordering`

Assign to a product via `product_tax_id`.

---

## Images (file browser)

| Tool | Description |
|------|-------------|
| `images_list` | List uploaded files in a server directory |

**Parameters:** `list[display]` — directory to browse: `product`, `category`, `manufacturer`, `attribute`, `video`, `demofiles`, `salefiles`

---

## Field notation

- `*` — required when creating
- `image.url` — server downloads the image from the URL and stores the filename
- `image.base64` — pass a data URI (`data:image/jpeg;base64,...`), server saves and stores the filename
- `image` / `image_name` — reference an already-uploaded file by its filename
