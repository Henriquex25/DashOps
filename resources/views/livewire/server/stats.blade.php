<div wire:poll.60s="getStats">
    <x-stats cols="4">
        <x-stats.stat :label="__('CPU')" :value="$stats['cpu']" :description="__('In use')"/>
        <x-stats.stat :label="__('Memory')" value="{{ $stats['memory']['percentage'] }}%" :description="$stats['memory']['description']" color="yellow" />
        <x-stats.stat :label="__('Storage')" value="{{ $stats['storage']['percentage'] }}%" :description="$stats['storage']['description']" color="green" />
        <x-stats.stat :label="__('Uptime')" :value="$stats['uptime']" color="red" size="2xl" />
    </x-stats>
</div>
