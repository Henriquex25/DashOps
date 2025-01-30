<?php

namespace App\View\Components\Stats;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Stat extends Component
{
    public function __construct(
        public string $label = '',
        public string $value = '',
        public string $description = '',
        public string $color = 'primary',
        public string $size = '3xl',
    ) {
        //
    }

    public function getColor(): string
    {
        $colors = [
            'primary' => 'text-primary-500',
            'yellow'  => 'text-yellow-500',
            'green'   => 'text-green-500',
            'red'     => 'text-red-500'
        ];

        return $colors[$this->color];
    }

    public function getBarColor(): string
    {
        $colors = [
            'primary' => 'bg-primary-500',
            'yellow'  => 'bg-yellow-500',
            'green'   => 'bg-green-500',
            'red'     => 'bg-red-500'
        ];

        return $colors[$this->color];
    }

    public function getSize(): string
    {
        $sizes = [
            'sm'  => 'text-sm',
            'md'  => 'text-md',
            'lg'  => 'text-lg',
            'xl'  => 'text-xl',
            '2xl' => 'text-2xl',
            '3xl' => 'text-3xl',
            '4xl' => 'text-4xl',
            '5xl' => 'text-5xl',
        ];

        return $sizes[$this->size];
    }

    public function render(): View|Closure|string
    {
        return view('components.stats.stat');
    }
}
