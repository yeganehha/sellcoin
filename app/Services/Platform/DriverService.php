<?php

namespace App\Services\Platform;

use App\Platforms\PlatformInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

class DriverService
{

    /**
     * check string is name of special platform driver or not
     * @param string $name
     * @return bool
     */
    public static function isValidDriver(string $name) : bool
    {
        if (class_exists($name) and is_subclass_of($name, PlatformInterface::class))
            return true;
        return false;
    }


    /**
     * Get List Of active Platform drivers.
     * @return Collection
     */
    public static function listDrivers() : Collection
    {
        $drivers = [];
        foreach ((new Finder)->in(app_path('Platforms\Drivers'))->files() as $driver) {
            $driver = app()->getNamespace().str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($driver->getRealPath(), realpath(app_path()).DIRECTORY_SEPARATOR)
                );

            if (self::isValidDriver($driver)) {
                $drivers[$driver] = new $driver();
            }
        }
        return  collect($drivers);
    }

    /**
     * Get name of special driver.
     * @return Collection
     */
    public static function driverName(string $name) : string
    {
        if ( ! self::isValidDriver($name))
            throw new InvalidArgumentException("Driver [{$name}] not found.");
        return  ($name)::$driver_name ?? $name;
    }
}
