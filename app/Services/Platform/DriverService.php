<?php

namespace App\Services\Platform;

use App\Exceptions\CoinNotFoundException;
use App\Models\Coin;
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

    /**
     * Get special driver object.
     * @param string $name
     * @return PlatformInterface
     */
    public static function getDriver(string $name) : PlatformInterface
    {
        if ( ! self::isValidDriver($name))
            throw new InvalidArgumentException("Driver [{$name}] not found.");
        return  new $name();
    }

    /**
     * Get special driver object.
     * @param mixed $platform
     * @return Collection
     */
    public static function coins(mixed $platform) : Collection
    {
        $platform = PlatformService::find($platform);
        return cache()->remember('listCoinsOf' . $platform->id , config('setting.coins.list.cache_time' , 0) , function () use ($platform) {
            return $platform->driver->coins();
        });
    }

    /**
     * Get special driver object.
     * @param mixed $platform
     * @param mixed $coin
     * @return Coin
     * @throws CoinNotFoundException
     */
    public static function coin(mixed $platform , mixed $coin) : Coin
    {
        $platform = PlatformService::find($platform);
        return cache()->remember('coinOf' . $platform->id .'_'.$coin , config('setting.coins.cache_time' , 0) , function () use ($platform,$coin)  {
            return $platform->driver->getCoin($coin);
        });
    }
}
