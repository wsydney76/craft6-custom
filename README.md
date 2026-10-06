# Craft 6 Custom

This is a customized Craft 6 DDEV starter project which installs just the bare minimum to get you started with Craft CMS 6. It includes a basic setup with some things you will need in every project, and some examples of how to use them.

## Disclaimer

Craft 6 is still in alpha, so anything may break anytime.

## Installation

Clone this repository into a new project directory `git clone https://github.com/wsydney76/craft6-custom your-project` and run `setup/install` in it, or execute the included steps manually, adjusted to your needs.

> `setup/install` is not executable by default, so you may need to run `chmod +x setup/install` first, or run `bash setup/install` to execute it.

> The git repository contains a `seed` DDEV database snapshot, so you can skip the Craft CMS installation if you run DDEV v1.25.4+. Otherwise, uncomment the `ddev exec craft install` line in `setup/install` to run the Craft CMS installation.
 
> The seeded database comes with an admin user `admin` with password `craft6-custom`, so you can log in to the control panel immediately after installation.

## Changes

### Composer

Added `craft:migrate:up` to the `post-update-cmd` so that migrations are run automatically after a `composer update`.

### Vite Integration

Updated `.ddev/config.yaml`, `vite.config.js` and `resources/js/app.js` according to [DDEV Vite Integration](https://docs.ddev.com/en/stable/users/usage/vite/#vite-integration), so that the Vite development server `ddev npm run dev` will work with automatic reloading.

### Content Model

Added  `Home, Search, Article Index` single sections with a `Home` entry type (title, image, body fields).

Added an `Articles` channel section with an `Article` entry type (title, image, content builder).

Added a `Content Builder` matrix field with `Text`, `Image`, and `Heading` block types.

### Assets

Added an `images` asset volume  with `public/images` root directory.

Added a `Project Transformer` asset transformer with `public/dist/transforms` root directory..

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
* `<x-layouts.nav />` blade component for rendering a navigation menu, powered by `NavComposer` view composer.
* `<x-layouts.dark-mode-switcher />` blade component for switching between light and dark mode.
* `<x-latest-articles heading="..." :exclude="$entry" />` for rendering a list of latest articles, as example for a class based component.
* `<x-blocks :blocks="..." />` for rendering a content builder matrix field, with `blocks/{type}` components for each block type.

Added a `pages::search` Livewire component as example for a Livewire full-page component.

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

### IDE 

Added Prettier support for formatting `php,blade.php` files including sorting of Tailwind classes.

In PhpStorm, you can enable Prettier by going to `Settings > Languages & Frameworks > JavaScript > Prettier` and

* enable `Automatic Prettier configuration`
* add `,php,blade.php` to the `Run for fieles` list
* enable `Run on save`, `Run on paste`, and `Prefer prettier...` options.

Dropped `laravel-pint`.

Added `FauxCraft` file to enable autocompletion for often used variables in templates, such as `$entry`, `$image`, etc.
