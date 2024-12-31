import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import AlpineFloatingUI from "@awcodes/alpine-floating-ui";
import collapse from '@alpinejs/collapse'
import mask from '@alpinejs/mask'
import focus from '@alpinejs/focus'
import sort from '@alpinejs/sort'

Alpine.plugin(sort)
Alpine.plugin(focus)
Alpine.plugin(mask)
Alpine.plugin(AlpineFloatingUI);
Alpine.plugin(collapse);

Livewire.start()
