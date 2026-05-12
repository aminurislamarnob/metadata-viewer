# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A WordPress plugin ("Metadata Viewer") that surfaces metadata (custom fields) for posts/pages/CPTs, users, comments, taxonomy terms, WooCommerce products, and WooCommerce orders directly inside their native admin edit screens, plus a realtime client-side search/filter over the rendered table. It's a developer/debugging tool.

## Commands

- `composer update` — install dev dependencies (dev env). `composer update --no-dev` for production.
- `composer phpcs` — run PHP_CodeSniffer (`vendor/bin/phpcs -p -s`). Config in `phpcs.xml`: WordPress + WordPress-Extra rulesets, PHP 7.4+, min WP 5.4, text domain `metadata-viewer`, `assets/` excluded.
- `composer phpcbf` — auto-fix sniffer violations.
- `composer phpcs:report` — write violations to `phpcs-report.txt`.
- `bin/build.sh` — produce a distributable `build/metadata-viewer.zip` (copies whitelisted files, runs `composer install --no-dev --optimize-autoloader`). `chmod +x bin/build.sh` first. Tagged pushes also build/release via `.github/workflows/deploy.yml` (GitHub release + WordPress.org SVN deploy).
- Tests: `phpunit/phpunit ~8.5` + `wp-phpunit` are in `require-dev`, but there is currently no `tests/` directory or `phpunit.xml` — there is no working test suite to run.

## Architecture

- **Bootstrap** (`metadata-viewer.php`): guards on `ABSPATH`, defines `METADATA_VIEWER_FILE`, loads Composer autoload, calls `welabs_metadata_viewer()` → `MetadataViewer::init()`.
- **Namespace**: `WeLabs\MetadataViewer\`, PSR-4 mapped to `includes/` (`composer.json`).
- **`MetadataViewer`** (`includes/MetadataViewer.php`): `final` singleton + service container. Subclasses are instantiated in `init_classes()` (hooked on `init`, priority 4) and reached via magic `__get` (e.g. `welabs_metadata_viewer()->post_meta_data`). `define_constants()` sets `METADATA_VIEWER_DIR`/`_INC_DIR`/`_TEMPLATE_DIR`/`_PLUGIN_ASSET`/`_PLUGIN_VERSION` and the `_LOAD_STYLE`/`_LOAD_SCRIPTS` toggles. `get_template_path()` / `get_template( $name, $args )` resolve + include a file under `METADATA_VIEWER_TEMPLATE_DIR` (`get_template` extracts `$args`, fires `metadata_viewer_before/after_template_part`).
- **Per-object-type viewers** in `includes/`, each hooks the relevant edit screen and renders a metadata table:
  - `PostMetaData` — post edit screens (all post types, incl. `product`).
  - `UserMetaData` — user profile/edit screens.
  - `CommentMetaData` — `comment.php?action=editcomment`; `get_metadata( 'comment', ... )`.
  - `TaxonomyMetaData` — `{$taxonomy}_edit_form`; `get_metadata( 'term', ... )`.
  - `OrderMetaData` — WooCommerce order edit screen; HPOS-aware (see below).
  - `DokanMetaData` — Dokan vendor dashboard order (`dokan_order_content_inside_after`) and product (`dokan_product_content_inside_area_after`) pages; only when `function_exists( 'dokan' )`. Delegates order rendering to `OrderMetaData` (via `woo_order_meta_data`); products use `get_metadata( 'post', $id )`.
- **`Helpers`** — static utils: `get_metadata_table_view()` (includes the table template), `is_comment_edit_screen()`, `unserialize_metadata_recursive()`.
- **`Assets`** (`includes/Assets.php`) — registers/enqueues admin JS & CSS; handles use the `metadata_viewer_*` prefix, version-busted with `METADATA_VIEWER_PLUGIN_VERSION`.
- **Templates** (`templates/`): `metadata-viewer-table.php` (generic table; search icon filterable via `metadata_viewer_search_icon_html`), `order-metadata-viewer-table.php` (uses `metadata_viewer_order_search_icon_html`), `taxonomy-metadata-viewer.php`, `dokan-order-metadata-viewer-panel.php`, `dokan-product-metadata-viewer-panel.php`. The wrapper templates are loaded via `get_template()` and fire a `metadata_viewer_*_body` action that the owning class hooks to print the table (keeps markup in templates, logic in classes). Every template starts with the `ABSPATH` guard; include them via `get_template()` / `Helpers`, never directly.
- **Front-end** (`assets/admin/`): `js/script.js` wires the realtime filter/search; CSS class names (`.metadata-viewer-wrapper`, `.metadata-viewer-table`, etc.) must stay in sync with the PHP templates. `assets/` is excluded from PHPCS.

## Conventions & gotchas

- Extend behavior via WordPress/WooCommerce actions & filters; never edit core. The plugin declares WooCommerce compat in the header and hooks `woocommerce_flush_rewrite_rules` — preserve that.
- **Do not switch the post meta viewer to `$product->get_meta_data()`** for products: WooCommerce hoists internal keys (SKU, price, stock…) into object props, so `get_meta_data()` is incomplete vs. the `postmeta` table. The viewer intentionally uses `get_metadata( 'post', $id )` for all post types.
- **`OrderMetaData` data source depends on HPOS state**: if `woocommerce_custom_orders_table_enabled` is `no`, OR HPOS is `yes` and `woocommerce_custom_orders_table_data_sync_enabled` is `yes` → use `get_metadata( 'post', $order_id )`. If HPOS is `yes` and sync is `no` → use the order object's `get_meta_data()` / `get_meta( $key, false )`.
- i18n: text domain `metadata-viewer`; use `__()`, `esc_html__()`, etc., matching existing strings. Escape output for context; sanitize input; nonce any new forms/AJAX.
- The codebase mixes tab-indented and space-indented files — match the file you're editing, don't reformat unrelated lines. Prefer strict comparisons (PHPCS enforces `StrictComparisons`, strict `in_array`).
