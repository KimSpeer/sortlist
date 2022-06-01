<?php

declare(strict_types=1);

namespace VendorName\Skeleton\Components\Blade;

class FirstBladeComponent extends Component
{
    /** @var array */
    protected static $assets = ['example'];

    /** @var string|null */
    public string $first_var = "";

    public function mount()
    {
        // mount
    }

    public function render()
    {
        return view('blade.first-blade-component');
    }
}
