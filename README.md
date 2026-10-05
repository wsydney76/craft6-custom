# Craft 6 Custom

This is a Craft 6 starter project which installs just the bare minimum to get you started with Craft CMS 6. It includes a basic setup with some things you will need in every project.

## Installation

Create a project directory and run `bash setup/install` in it, or execute the included steps manually, adjusted to your needs.

## Changes

### Composer

Added `craft:migrate:up` to the `post-update-cmd` so that migrations are run automatically after a `composer update`.

### Vite Integration

Updated `.ddev/config.yaml`, `vite.config.js` and `resources/js/app.js` according to [DDEV Vite Integration](https://docs.ddev.com/en/stable/users/usage/vite/#vite-integration), so that the Vite development server `ddev npm run dev` will work with automatic reloading.

### Livewire/Flux

Added Livewire and Flux (free) to the project.

Published Livewire config filed (setting the `high-voltage` emoji to `false`, sorry...).

### Blaze

Installed Blaze for performance improvements.

### Content Model

Added a `Home` single section with a `Home` entry type (title, image, body fields).

Added an `Articles` channel section with an `Article` entry type (title, image, content builder).

Added a `Content Builder` matrix field with `Text`, `Image`, and `Heading` block types.

### Assets

Added an `images` asset volume  with `public/images` root directory.

Added a `Project Transformer` asset transformer with `public/dist/transforms` root directory..

### Templates

Added `layouts/app.blade.php` layout template with minimal markup and dark mode support.

Added `_entries/home/show.blade.php` template for the home page with minimal markup.

Added `<x-markdown :text="$text" />` blade component.

Added `<x-img :image="..." width="..." height="..." />` blade component.

Added `<x-prose>...</x-prose>` blade component for rendering rich text with Tailwind CSS typography styles.

Added `<x-layouts.dark-mode-switcher />` blade component for switching between light and dark mode.

### Tailwind CSS

Added the [Typography](https://github.com/tailwindlabs/tailwindcss-typography) plugin to Tailwind CSS for better typography support.

### Fonts

Added custom fonts support, see [Laravel docs](https://laravel.com/framework/docs/13.x/vite#working-with-fonts).

### Theming

The layout follows [Flux theming conventions](https://fluxui.dev/docs/theming), using `zinc` as the default base color. 

You can apply your theming in `resources/css/app.css` by overriding this base color and the accent color used in Flux components.

See the [Flux theme builder](https://fluxui.dev/themes).

This adds `accent`, `accent-foreground` and `accent-content` colors to the Tailwind CSS color palette, including dark mode support.

### IDE 

Added Prettier support for formatting `php,blade.php` files including sorting of Tailwind classes.

In PhpStorm, you can enable Prettier by going to `Settings > Languages & Frameworks > JavaScript > Prettier` and

* enable `Automatic Prettier configuration`
* add `,php,blade.php` to the `Run for fieles` list
* enable `Run on save`, `Run on paste`, and `Prefer prettier...` options.

Dropped `laravel-pint`.

Added `FauxCraft` file to enable autocompletion for often used variables in templates, such as `$entry`, `$image`, etc.
