@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="flex flex-row border-bottom px-6 py-4">
        <div class="text-lg font-medium text-gray-900">
            {{ $title }}
        </div>
       
    </div>
    <div class="px-6 ps-4 pe-4 pb-4">
        <div class="mt-4 text-sm text-gray-600">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-between border-top px-6 py-4 text-right">
        {{ $footer }}
    </div>
</x-modal>
