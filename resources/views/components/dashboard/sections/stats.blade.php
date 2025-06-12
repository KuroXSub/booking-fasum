@props(['stats'])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($stats as $stat)
        <x-dashboard.cards.stat 
            :title="$stat['title']" 
            :value="$stat['value']" 
            :icon="$stat['icon']" 
            :color="$stat['color'] ?? 'blue'" />
    @endforeach
</div>