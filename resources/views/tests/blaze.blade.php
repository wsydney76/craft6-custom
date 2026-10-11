{{--
    Check if Blaze is working properly
    - run `php artisan view:clear` to clear the view cache
    - open this file in the browser /tests/blaze
    - inspect /storage/framework/views: There should only be one file with the button code inlined,
    - and no other files with the button code.
--}}
<h1>Testing a folded Blaze component</h1>
<flux:button variant="primary" color="red" icon="pencil">{{ now()->format('Y') }}</flux:button>
