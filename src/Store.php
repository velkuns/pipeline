<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Velkuns\Pipeline;

class Store
{
    /** @var array<string, mixed> $store */
    private static array $store = [];

    public static function flush(): void
    {
        self::$store = [];
    }

    public static function set(string $name, mixed $value): void
    {
        self::$store[$name] = $value;
    }

    public static function get(string $name): mixed
    {
        return self::$store[$name];
    }

    /**
     * @param string[] $names
     * @return list<mixed>
     */
    public static function getMany(...$names): array
    {
        $input = [];
        /** @var string $name */
        foreach ($names as $name) {
            if (!isset(self::$store[$name])) {
                continue;
            }

            $input[] = self::$store[$name];
        }

        return $input;
    }
}
