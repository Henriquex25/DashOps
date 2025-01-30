<div @class([
    'w-full grid grid-rows-1 gap-x-4 gap-y-4 py-2',
    $getCols($cols),
])>
    {{ $slot }}
</div>