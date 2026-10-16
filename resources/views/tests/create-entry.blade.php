@php
    use CraftCms\Cms\Support\Html;
    $section = Sections::getSectionByHandle('test');
    $type = EntryTypes::getEntryTypeByHandle('test');
    abort_unless($section && $type, 500);
@endphp

@craftRequireLogin

<x-layouts::app title="Create a Test Entry">
    @session('error')
        <flux:callout variant="danger" icon="exclamation-triangle" class="mb-4">
            <flux:callout.text>{{ $value }}</flux:callout.text>
        </flux:callout>
    @endsession

    @session('success')
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <flux:callout.text>{{ $value }}</flux:callout.text>
        </flux:callout>
    @endsession

    <form method="post" class="space-y-4">
        {!! Html::csrfInput() !!}
        {!! Html::actionInput('entries/save-entry') !!}
        {!! Html::hiddenInput('sectionId', $section->id) !!}
        {!! Html::hiddenInput('typeId', $type->id) !!}
        {!! Html::successMessageInput('Thank you for your contribution!') !!}
        {!! Html::failMessageInput('Entry could not be saved, please review and correct the errors!') !!}

        <flux:input label="Title" name="title" value="{{ old('title') }}" />

        @craftNamespace('fields')
        <flux:input label="Name" name="name" value="{{ old('fields.name') }}" />

        <flux:textarea label="Body" name="body">{{ old('fields.body') }}</flux:textarea>
        @endCraftNamespace

        <flux:button type="submit" variant="primary">Create Entry</flux:button>
    </form>

    {{--
        <div class="mt-8">
        @dump($errors)
        @dump(session()->all())
        </div>
    --}}
</x-layouts::app>
