@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="border-bottom px-6 py-4">
        <div class="text-lg font-medium text-gray-900 ">
            {{ $title }}
        </div>
       
    </div>
    <div class="pb-4">
        <div class="text-sm text-gray-600">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-between border-top px-6 py-4 text-right">
        {{ $footer }}
    </div>
</x-modal>
