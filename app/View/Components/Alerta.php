<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alerta extends Component
{
    public $tipoAlerta;
    /**
     * Create a new component instance.
     */
    public function __construct($tipoAlerta = 'info')
    {
        $this -> tipoAlerta = $tipoAlerta;
    }

    /*
     * Create a new component instance.
     
    public function __construct()
    {
        //
    }*/

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alerta');
    }
}
