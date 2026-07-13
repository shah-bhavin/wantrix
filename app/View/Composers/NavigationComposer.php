<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Support\Navigation;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'navigation' => Navigation::menu(),
            'page' => Navigation::current(),
        ]);
    }
}