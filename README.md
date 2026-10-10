# Craft 6 Custom

This is a customized Craft 6 DDEV starter project. It includes a basic setup with some things you will need in every project, and some examples of more advanced techniques.

For a more basic starter without content model and any examples, see [Craft 6 Basic](https://github.com/wsydney76/craft6-basic).


## Disclaimer

Craft 6 is still in alpha, so anything may break anytime.

## Installation

Clone this repository into a new project directory `git clone https://github.com/wsydney76/craft6-custom your-project` and run `setup/install your-project` in it, or execute the included steps manually, adjusted to your needs.

> `setup/install` is not executable by default, so you may need to run `chmod +x setup/install` first, or run `bash setup/install` to execute it.

> The git repository contains a `seed` DDEV database snapshot, so the setup script skips the Craft CMS installation. If you do not run DDEV v1.25.4+, pull in the seed db via `ddev snapshot restore`, or run `craft install` manually and provide your own content.
 
> The seeded database comes with an admin user `admin` with password `craft6-custom`, so you can log in to the control panel immediately after installation.

> Ships with free-to-use images in the `public/images/seed` directory.

## Changes

### Composer

* Added `craft:migrate:up` to the `post-update-cmd` so that migrations are run automatically after a `composer update`.
* Clear Laravel and Craft caches after a `composer update` to avoid issues with stale caches.

`composer.json` requires `6.x-dev` versions of `craftcms/cms` and `craftcms/cms-assets` packages. Run `composer update` to get the latest versions.

Or go back to tagged releases of Craft CMS 6 by changing the version constraints in `composer.json` to `^6.0.0` and run `composer update`.

### Config

Added a couple of asset-related settings to `config/craft/general.php` to improve image quality, cache busting and ASCII support.

### Vite Integration

Updated `.ddev/config.yaml`, `vite.config.js` and `resources/js/app.js` according to [DDEV Vite Integration](https://docs.ddev.com/en/stable/users/usage/vite/#vite-integration), so that the Vite development server `ddev npm run dev` will work with automatic reloading.

### Content Model

Added  `Home, Search, Article Index` single sections with a `Home` entry type (title, image, body fields).

Added an `Articles` channel section with an `Article` entry type (title, image, content builder).

Added a `Content Builder` matrix field with `Text`, `Image`, and `Heading` block types.

### Assets

Added an `images` asset volume  with `public/images` root directory.

Added an `Images Transformer` asset transformer with `public/dist/transforms/images` root directory.

> Convention: Set up a dedicated asset transformer for each asset volume to match paths.

### Routing

Using named routes for entries, pointing to controller actions.

While this requires duplicate URI definitions, it enforces a consistent setup.

Using named routes in templates, e.g. `route('articles.index)` makes templates independent of changes to the URI, and/or avoids additional queries, for example, to retrieve the actual URL of a single entry.

### Templates

For simplicity, templates use Flux components. Adjust or replace to match your design.

Added templates:

* `layouts/app.blade.php` layout template with minimal markup and dark mode support.
* `_entries/home/show.blade.php` template for the home page with minimal markup.
* `_entries/article/index.blade.php` template for an article listing page, powered by `ArticleController`.
* `_entries/article/show.blade.php` template for an article page with minimal markup.

Added Blade components:

* `<x-markdown :text="$text" />` blade component.
* `<x-nl2br :text="$text" />` blade component.
* `<x-img :image="..." width="..." height="..." />` blade component.
* `<x-prose>...</x-prose>` blade component for rendering rich text with Tailwind CSS typography styles.
* `<x-layouts.dark-mode-switcher />` blade component for switching between light and dark mode.
* `<x-latest-articles heading="..." :exclude="$entry" />` for rendering a list of latest articles, as example for a class based component.
* `<x-blocks :blocks="..." />` for rendering a content builder matrix field, with `blocks/{type}` components for each block type.
* `<x-partials.widget>` for rendering a widget-like card.

Added Livewire components:

* `<x-layouts.nav />` for rendering a navigation menu.
* `<livewire:articles.new />` for rendering a paginated list of latest articles on the home page.
* `pages::search` Livewire component as example for a Livewire full-page component.
* `pages::contact` Livewire component for contact form with user notification.
* `pages::notifications` Livewire component for displaying these notifications.

### Content Representations

For articles (index and show pages), added simplified content representations for JSON and PDF, as well as a Twig template, using a `format=` query parameter.

Installed `barryvdh/laravel-dompdf` for PDF generation.

### Livewire/Flux

Added Livewire and Flux (free) to the project.

Published Livewire config filed (setting the `high-voltage` emoji to `false`, sorry...).

### Blaze

Installed Blaze for performance improvements.

### Tailwind CSS

Added the [Typography](https://github.com/tailwindlabs/tailwindcss-typography) plugin to Tailwind CSS for better typography support.

### Fonts

Added custom fonts support, see [Laravel docs](https://laravel.com/framework/docs/13.x/vite#working-with-fonts).

### Theming

The layout follows [Flux theming conventions](https://fluxui.dev/docs/theming), using `zinc` as the default base color. 

You can apply your theming in `resources/css/app.css` by overriding this base color and the accent color used in Flux components.

See the [Flux theme builder](https://fluxui.dev/themes).

This adds `accent`, `accent-foreground` and `accent-content` colors to the Tailwind CSS color palette, including dark mode support.

### Routes

Added a `'tests/{template}' route to test templates in the browser, e.g. `tests/home` will render the `resources/views/tests/home` template.

### Testing

Installed Pest for testing.

* Added a `tests/Feature/ContactPageLivewireTest.php` test.
* Added a `tests/Feature/NotificationsPageLivewireTest.php` test.
* Added a `tests/Feature/ArticleShowPageTest.php` test.
* Added a `tests/Feature/ArticleShowFormatsTest.php` test.
* Added a `tests/Feature/HomeControllerTest.php` test.

Run with `artisan test`.

Tests are (mostly) AI generated, so they may not be perfect. Please check and adjust them to your needs.

### IDE 

Added Prettier support for formatting `php,blade.php` files including sorting of Tailwind classes.

In PhpStorm, you can enable Prettier by going to `Settings > Languages & Frameworks > JavaScript > Prettier` and

* enable `Automatic Prettier configuration`
* add `,php,blade.php` to the `Run for files` list
* enable `Run on save`, `Run on paste`, and `Prefer prettier...` options.

Note: Prettier may mess up comments in `@props` directives, so we follow the convention to put comments into a separate `@php` block, e.g.

```blade
@props([
    'entry',
])

@php
    /** @var CraftCms\Cms\Entry\Elements\Article $entry */
@endphp
```

Dropped `laravel-pint`.

Added `FauxCraft` file to enable autocompletion for often used variables in templates, such as `$entry`, `$image`, etc.

## AI Support

Not yet. In local dialect: 

> Mia glangt dass i woas das i kannt wann i woin dad. Aber i duas ned, weil i muas ned.
