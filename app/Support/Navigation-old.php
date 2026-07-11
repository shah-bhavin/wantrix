<?php

namespace App\Support;

class Navigation
{
    public static function current(): array
    {
        $route = request()->route()?->getName();

        foreach (config('navigation') as $item) {

            if (isset($item['route']) && $item['route'] === $route) {
                return $item;
            }

            if (isset($item['children'])) {
                foreach ($item['children'] as $child) {
                    if ($child['route'] === $route) {
                        return $child;
                    }
                }
            }
        }

        return [
            'label' => 'Dashboard',
            'title' => 'Dashboard',
            'description' => '',
        ];
    }
}