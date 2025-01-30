<?php

namespace App\View\Components\Stats;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Stats extends Component
{
    public function __construct(
        public int $cols = 4,
    ) {
        //
    }

    public function getCols(int $col): string
    {
        $cols = [
            1  => 'grid-cols-1',
            2  => 'grid-cols-2',
            3  => 'grid-cols-3',
            4  => 'grid-cols-4',
            5  => 'grid-cols-5',
            6  => 'grid-cols-6',
            7  => 'grid-cols-7',
            8  => 'grid-cols-8',
            9  => 'grid-cols-9',
            10 => 'grid-cols-10',
            11 => 'grid-cols-11',
            12 => 'grid-cols-12',
        ];

        return $cols[$col];
    }

    public function render(): View|Closure|string
    {
        return view('components.stats.stats');
    }
}
