<div class="h-36 bg-[#363b47] rounded-lg shadow-smooth flex flex-col justify-around pr-5 pl-7 py-3.5 relative overflow-hidden">
    <div @class([
        'absolute top-0 left-0 h-full w-2 opacity-60',
        $getBarColor(),
    ])></div>
    <div>
        <span @class([
            'h-full font-semibold text-sm',
            $getColor(),
        ])>{{ $label }}</span>
    </div>
    <div>
        <span @class([
            'text-gray-200 font-semibold',
            $getSize(),
        ])>{{ $value }}</span>
    </div>
    <div class="mt-1 h-5">
        <span class="text-gray-400 text-sm">{{ $description }}</span>
    </div>
</div>