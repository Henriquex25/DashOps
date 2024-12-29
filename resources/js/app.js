import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import AlpineFloatingUI from "@awcodes/alpine-floating-ui";
import collapse from '@alpinejs/collapse'

Alpine.plugin(AlpineFloatingUI);
Alpine.plugin(collapse);
Alpine.start()
