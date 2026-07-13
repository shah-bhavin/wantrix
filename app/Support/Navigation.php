<?php

namespace App\Support;

class Navigation
{
    public static function menu(): array
    {
        return config('navigation');
    }

    public static function current(): ?array
    {
        return self::findRoute(
            self::menu(),
            request()->route()?->getName()
        );
    }

    public static function title(): string
    {
        return self::current()['title']
            ?? config('app.name');
    }

    public static function description(): string
    {
        return self::current()['description']
            ?? '';
    }

    public static function icon(): ?string
    {
        return self::current()['icon']
            ?? null;
    }

    protected static function findRoute(array $items, ?string $route): ?array
    {
        foreach ($items as $item) {

            if (
                isset($item['route']) &&
                $item['route'] === $route
            ) {
                return $item;
            }

            if (isset($item['children'])) {

                $child = self::findRoute(
                    $item['children'],
                    $route
                );

                if ($child) {
                    return $child;
                }
            }
        }

        return null;
    }
}