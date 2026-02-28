# Agency Custom Plugin

A WordPress plugin for digital agencies that registers **Progetti** (Projects) and **Servizi** (Services) custom post types, defines ACF field groups entirely in code, exposes a custom REST API with filtering and pagination, and enqueues a Three.js 3D experience with OrbitControls.

> **Repository:** `agency-wp-plugin`  
> **Plugin Slug:** `agency-plugin`  
> **Version:** 1.0.0  
> **Author:** Agency Dev Team  
> **License:** GPLv2 or later

---

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [File Structure](#file-structure)
- [Custom Post Types](#custom-post-types)
- [ACF Fields](#acf-fields)
- [REST API Endpoints](#rest-api-endpoints)
- [Three.js Integration](#threejs-integration)
- [Hooks & Filters](#hooks--filters)
- [Contributing](#contributing)
- [License](#license)

---

## Features

- **Custom Post Types:** `Progetti` (Projects) & `Servizi` (Services) â fully translatable labels, REST API enabled, custom rewrite slugs.
- **ACF Field Groups defined in code:** All field groups and fields are registered programmatically via `acf_add_local_field_group()`, making the setup portable and independent from the ACF UI export/import workflow.
- **Custom REST API endpoints:** Namespace `agency/v1`, supporting filtering, sorting, and pagination for both CPTs, plus single-item endpoints.
- **Three.js experience:** A self-contained 3D canvas experience (`experience.js`) using Three.js r158 and OrbitControls, enqueued only on pages where needed via a shortcode or body class.

---

## Requirements

| Requirement | Minimum Version |
|---|---|
| WordPress | 6.0+ |
| PHP | 7.4+ |
| Advanced Custom Fields PRO | 6.0+ |
| Node / npm *(only for asset builds)* | 18+ |

> **Note:** ACF PRO must be installed and activated. The plugin registers all field groups in PHP code so you do **not** need to export/import JSON from the ACF UI, but the ACF plugin itself must be present as a dependency.

---

## Installation

### Manual (recommended for production)

1. Clone or download this repository into your plugins directory:

   ```bash
   cd wp-content/plugins/
   git clone https://github.com/your-org/agency-wp-plugin.git agency-plugin
   ```

2. Activate the plugin from the **WordPress Admin â Plugins** screen.

3. Install and activate **Advanced Custom Fields PRO** (version 6.0 or higher).

4. Custom Post Types and ACF field groups are **automatically registered** on activation â no additional setup required.

5. Visit **Settings â Permalinks** and click **Save Changes** to flush rewrite rules after activation.

### Via WP-CLI

```bash
wp plugin install path/to/agency-plugin.zip --activate
```

### Verifying Installation

After activation you should see:

- **Progetti** and **Servizi** in the WordPress admin sidebar.
- ACF field groups *Dettagli Progetto* and *Dettagli Servizio* listed under **ACF â Field Groups** (shown as *Inactive / Local* because they are code-defined).
- REST API routes available at `/wp-json/agency/v1/`.

---

## File Structure

```
agency-plugin/
âââ agency-plugin.php          # Main plugin bootstrap file (headers, loader)
âââ includes/
â   âââ cpt-progetti.php       # Registers the Progetti custom post type
â   âââ cpt-servizi.php        # Registers the Servizi custom post type
â   âââ acf-fields.php         # Defines all ACF field groups in PHP code
â   âââ rest-api.php           # Custom REST API endpoints (agency/v1)
â   âââ enqueue-threejs.php    # Enqueues Three.js + OrbitControls + experience.js
âââ assets/
â   âââ js/
â   â   âââ experience.js      # Three.js scene: renderer, camera, OrbitControls, animation loop
â   âââ css/
â       âââ experience.css     # Canvas wrapper styles, loader overlay
âââ README.md
```

---

## Custom Post Types

### Progetti (Projects)

| Property | Value |
|---|---|
| Post type key | `progetti` |
| REST base | `progetti` |
| Rewrite slug | `progetti` |
| Supports | `title`, `editor`, `thumbnail`, `excerpt` |
| Taxonomies | `categoria_progetto` (custom), `post_tag` |
| Public | `true` |
| Show in REST | `true` |

### Servizi (Services)

| Property | Value |
|---|---|
| Post type key | `servizi` |
| REST base | `servizi` |
| Rewrite slug | `servizi` |
| Supports | `title`, `editor`, `thumbnail`, `excerpt`, `page-attributes` |
| Taxonomies | `categoria_servizio` (custom) |
| Public | `true` |
| Show in REST | `true` |

---

## ACF Fields

All fields are defined in `includes/acf-fields.php` using `acf_add_local_field_group()`. No JSON sync or ACF UI interaction is required.

### Progetti â Field Group: *Dettagli Progetto*

Shows on post type `progetti`.

| Field Label | Field Name | Type | Description |
|---|---|---|---|
| Cliente | `cliente` | Text | Client / company name |
| Anno | `anno` | Number | Year the project was completed |
| Categoria Progetto | `categoria_progetto` | Taxonomy | Links to `categoria_progetto` taxonomy |
| URL Progetto | `url_progetto` | URL | External link to live project |
| Gallery | `gallery_progetto` | Gallery | Image gallery (multiple images) |
| Tecnologie Usate | `tecnologie` | Checkbox | Predefined tech stack options |
| Descrizione Breve | `descrizione_breve` | Textarea | Short summary for cards / API |
| In Evidenza | `in_evidenza` | True/False | Feature on homepage |
| Colore Brand | `colore_brand` | Color Picker | Hex color for project accent |
| Video URL | `video_url` | oEmbed | YouTube / Vimeo embed URL |

### Servizi â Field Group: *Dettagli Servizio*

Shows on post type `servizi`.

| Field Label | Field Name | Type | Description |
|---|---|---|---|
| Icona | `icona_servizio` | Image | SVG or raster icon |
| Tagline | `tagline` | Text | One-line marketing description |
| Descrizione Lunga | `descrizione_lunga` | WYSIWYG | Full rich-text description |
| Is Featured | `is_featured` | True/False | Mark as a featured service |
| Prezzo Da | `prezzo_da` | Number | Starting price (optional) |
| Durata Stimata | `durata_stimata` | Text | Estimated delivery timeline |
| Tecnologie | `tecnologie_servizio` | Checkbox | Tech stack used in this service |
| CTA Label | `cta_label` | Text | Call-to-action button text |
| CTA URL | `cta_url` | URL | Call-to-action destination URL |
| Ordine | `ordine` | Number | Manual display order weight |

---

## REST API Endpoints

All endpoints are registered under the `agency/v1` namespace and are publicly accessible (read-only). Authentication is required for `POST`, `PUT`, or `DELETE` operations if extended in future versions.

Base URL: `https://your-site.com/wp-json/agency/v1/`

---

### `GET /wp-json/agency/v1/progetti`

Returns a paginated list of published Progetti posts.

**Parameters:**

| Parameter | Type | Default | Description |
|---|---|---|---|
| `per_page` | integer | `10` | Number of items per page (max 100) |
| `page` | integer | `1` | Page number |
| `categoria` | string | â | Slug of `categoria_progetto` taxonomy term to filter by |
| `in_evidenza` | boolean | â | Pass `true` to return only featured projects |
| `orderby` | string | `date` | Sort field: `date`, `title`, `anno`, `menu_order` |
| `order` | string | `DESC` | Sort direction: `ASC` or `DESC` |

**Example Request:**

```http
GET /wp-json/agency/v1/progetti?per_page=6&page=1&categoria=web-design&orderby=anno&order=DESC
```

**Example Response:**

```json
{
  "total": 24,
  "total_pages": 4,
  "page": 1,
  "per_page": 6,
  "data": [
    {
      "id": 42,
      "title": "Redesign E-Commerce Fashion",
      "slug": "redesign-ecommerce-fashion",
      "excerpt": "Progetto di redesign completo...",
      "thumbnail": "https://your-site.com/wp-content/uploads/2024/project.jpg",
      "cliente": "Brand XYZ",
      "anno": 2024,
      "categoria": "web-design",
      "url_progetto": "https://brandxyz.com",
      "tecnologie": ["React", "WooCommerce"],
      "in_evidenza": true,
      "colore_brand": "#e63946",
      "link": "https://your-site.com/progetti/redesign-ecommerce-fashion/"
    }
  ]
}
```

---

### `GET /wp-json/agency/v1/progetti/{id}`

Returns a single Progetto by its WordPress post ID.

**Parameters:**

| Parameter | Type | Required | Description |
|---|---|---|---|
| `id` | integer | Yes | WordPress post ID |

**Example Request:**

```http
GET /wp-json/agency/v1/progetti/42
```

**Error Response (404):**

```json
{
  "code": "progetto_not_found",
  "message": "Progetto not found.",
  "data": { "status": 404 }
}
```

---

### `GET /wp-json/agency/v1/servizi`

Returns a paginated list of published Servizi posts.

**Parameters:**

| Parameter | Type | Default | Description |
|---|---|---|---|
| `per_page` | integer | `10` | Number of items per page (max 100) |
| `page` | integer | `1` | Page number |
| `is_featured` | boolean | â | Pass `true` to return only featured services |
| `orderby` | string | `menu_order` | Sort field: `date`, `title`, `menu_order`, `ordine` |
| `order` | string | `ASC` | Sort direction: `ASC` or `DESC` |

**Example Request:**

```http
GET /wp-json/agency/v1/servizi?per_page=12&is_featured=true&orderby=menu_order&order=ASC
```

**Example Response:**

```json
{
  "total": 8,
  "total_pages": 1,
  "page": 1,
  "per_page": 12,
  "data": [
    {
      "id": 15,
      "title": "Web Design & Development",
      "slug": "web-design-development",
      "tagline": "Siti web moderni, veloci e scalabili.",
      "descrizione_lunga": "<p>Progettiamo e sviluppiamo...</p>",
      "icona": "https://your-site.com/wp-content/uploads/icon-web.svg",
      "is_featured": true,
      "prezzo_da": 1500,
      "durata_stimata": "4-8 settimane",
      "tecnologie": ["WordPress", "React", "Next.js"],
      "cta_label": "Scopri di piÃ¹",
      "cta_url": "https://your-site.com/servizi/web-design-development/",
      "ordine": 1,
      "link": "https://your-site.com/servizi/web-design-development/"
    }
  ]
}
```

---

### `GET /wp-json/agency/v1/servizi/{id}`

Returns a single Servizio by its WordPress post ID.

**Parameters:**

| Parameter | Type | Required | Description |
|---|---|---|---|
| `id` | integer | Yes | WordPress post ID |

**Example Request:**

```http
GET /wp-json/agency/v1/servizi/15
```

**Error Response (404):**

```json
{
  "code": "servizio_not_found",
  "message": "Servizio not found.",
  "data": { "status": 404 }
}
```

---

## Three.js Integration

The plugin optionally enqueues a Three.js r158 WebGL experience on pages where the shortcode `[agency_experience]` is used or when the body class `has-3d-experience` is detected.

### Enqueued Scripts

| Handle | Source | Version |
|---|---|---|
| `three-js` | CDN: `unpkg.com/three@0.158.0/build/three.module.js` | 0.158.0 |
| `three-orbit-controls` | CDN: `unpkg.com/three@0.158.0/examples/jsm/controls/OrbitControls.js` | 0.158.0 |
| `agency-experience` | `assets/js/experience.js` (local) | Plugin version |

### Shortcode

To embed the Three.js canvas in any page or post:

```
[agency_experience height="600px" bg_color="#0a0a0a"]
```

| Attribute | Default | Description |
|---|---|---|
| `height` | `500px` | CSS height of the canvas container |
| `bg_color` | `#000000` | Background color of the scene |

### `assets/js/experience.js` Overview

- Initializes a `THREE.WebGLRenderer` with antialiasing and pixel ratio awareness.
- Sets up a `PerspectiveCamera` with configurable FOV.
- Attaches `OrbitControls` with damping enabled.
- Adds ambient and directional lights.
- Runs a `requestAnimationFrame` loop with `controls.update()` and `renderer.render()`.
- Handles `resize` events to keep the aspect ratio correct.
- All Three.js objects are scoped to an IIFE to avoid global namespace pollution.

---

## Hooks & Filters

The plugin exposes the following WordPress hooks for extensibility:

### Actions

| Hook | Description |
|---|---|
| `agency_plugin_loaded` | Fires after the plugin has loaded all includes. |
| `agency_before_register_cpts` | Fires before CPTs are registered. |
| `agency_after_register_cpts` | Fires after CPTs are registered. |
| `agency_rest_api_init` | Fires inside the `rest_api_init` callback, after agency routes are registered. |

### Filters

| Hook | Parameters | Description |
|---|---|---|
| `agency_progetti_rest_response` | `$data, $post` | Filter the REST response array for a single Progetto. |
| `agency_servizi_rest_response` | `$data, $post` | Filter the REST response array for a single Servizio. |
| `agency_progetti_query_args` | `$args, $request` | Filter the `WP_Query` args for the Progetti list endpoint. |
| `agency_servizi_query_args` | `$args, $request` | Filter the `WP_Query` args for the Servizi list endpoint. |
| `agency_threejs_enqueue` | `$should_enqueue` | Return `false` to prevent Three.js scripts from being enqueued. |

**Example â adding a custom field to the Progetti REST response:**

```php
add_filter( 'agency_progetti_rest_response', function( $data, $post ) {
    $data['custom_field'] = get_post_meta( $post->ID, 'custom_field', true );
    return $data;
}, 10, 2 );
```

---

## Contributing

1. Fork the repository and create a feature branch:

   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) for all PHP code.

3. Test REST endpoints with [Postman](https://www.postman.com/) or [Insomnia](https://insomnia.rest/).

4. Open a Pull Request with a clear description of the change.

### Code Style

- PHP: WordPress Coding Standards (WPCS) via PHPCS.
- JS: ESLint with WordPress recommended config.
- Tabs for indentation in PHP; 2-space in JS/JSON.

---

## Changelog

### 1.0.0 â 2024-01-01

- Initial release.
- Registers `progetti` and `servizi` CPTs.
- ACF field groups defined in code for both CPTs.
- Custom REST API endpoints: list + single for both CPTs.
- Three.js experience with OrbitControls via `[agency_experience]` shortcode.

---

## License

This plugin is licensed under the **GNU General Public License v2.0 or later**.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

See [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html) for the full license text.
