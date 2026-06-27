# Free Widgets For Elementor

A lightweight, **100% free** collection of essential [Elementor](https://wordpress.org/plugins/elementor/) widgets — performance-first, accessible, and built with clean code. No upsells, ever.

[![CI](https://github.com/devmonowar/free-widgets-for-elementor/actions/workflows/ci.yml/badge.svg)](https://github.com/devmonowar/free-widgets-for-elementor/actions/workflows/ci.yml)

> **Note:** This is a complete, from-scratch build. The plugin shares only the WordPress.org slug (`free-widgets-for-elementor`) with the previous version — no code, data or UI is carried over.

## Features

- **10 essential widgets** for the Elementor editor, grouped under their own **Free Widgets** category.
- **Performance-first:** each widget's CSS/JS loads only on pages where the widget is actually used (via Elementor's `get_style_depends()` / `get_script_depends()`). Nothing unused is ever queued.
- **No bloat:** plain CSS and vanilla JavaScript — no jQuery dependency for the widgets, no heavy libraries, **no build step**.
- **Accessible (A11Y):** interactive widgets (Tabs, Accordion) ship correct ARIA roles/states and full keyboard support (arrow keys, Home/End, Enter/Space).
- **Container ready:** works inside Elementor's Flexbox and CSS Grid containers.
- **Admin dashboard** to enable/disable individual widgets, set global design defaults (border radius, shadow, typography), and view system info — all in a single, lightweight option.
- **Translation ready & RTL friendly.** 100% free, GPL licensed — no account or license key.

## Included widgets

| Widget | Notes |
| --- | --- |
| Heading | Tag, link, alignment, typography |
| Button | Icon, sizes, hover styles |
| Image | Responsive, caption, link |
| Icon Box | Top / left / right layouts |
| Counter | Count-up on scroll (IntersectionObserver) |
| Team | Photo, role, bio, social links |
| Testimonial | Quote, avatar, name, title |
| Accordion | Accessible, keyboard navigation |
| Tabs | WAI-ARIA tabs, horizontal/vertical |
| Call To Action | Title, text, button, background |

## Requirements

- WordPress 6.0+
- PHP 7.4+
- [Elementor](https://wordpress.org/plugins/elementor/) (free) — Elementor Pro is **not** required.

## Architecture

- Namespace `FWFE`, prefix `fwfe_`, CSS class prefix `fwfe-`.
- **Manual class loading** (`includes/Core/Loader.php`) via `require_once` — no Composer, no PSR-4 autoloader, no `vendor/`.
- **No build tool** — plain CSS/JS written straight into `assets/`. Asset cache-busting uses the plugin version (`time()` while `WP_DEBUG`).
- `includes/Core/` (Plugin, Loader, Assets) · `includes/Hooks/` (Admin, Frontend, Elementor) · `includes/Base/Widget_Base.php` · `includes/Helpers/` · `includes/Admin/` · `includes/Widgets/<Name>/Widget.php`.
- Single option `fwfe_settings`. Clean uninstall (`uninstall.php`).

## Development

No build step and no Composer dependency are required to run the plugin. For coding-standards checks, install PHP_CodeSniffer + the WordPress standards globally and run:

```bash
phpcs --standard=phpcs.xml.dist .
phpcbf --standard=phpcs.xml.dist .   # auto-fix where possible
```

PHP 7.4+ compatible. Coding standard: WordPress-Core (see `phpcs.xml.dist`). CI runs PHP lint (7.4–8.3) + PHPCS on every push.

## Release workflow

1. Bump the version in **2 places**: the `Version:` header in `free-widgets-for-elementor.php` (the single source of truth — `FWFE_VERSION` is read from it) and `readme.txt` (`Stable tag` + changelog).
2. Commit and push to `main`. Wait for **CI** (lint + PHPCS) to pass.
3. Tag the release: `git tag X.Y.Z && git push origin X.Y.Z`. The **Deploy** workflow publishes it to WordPress.org.

Requires GitHub secrets `SVN_USERNAME` and `SVN_PASSWORD` (a WordPress.org account with commit access to the plugin).

## License

[GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html).
