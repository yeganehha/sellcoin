<?php

namespace App\Services\Platform;

use App\Models\Platform;
use App\Platforms\PlatformInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use \InvalidArgumentException;

class PlatformService
{
    /**
     * @param string $name
     * @param string|PlatformInterface $driver
     * @param float $deposit_tether
     * @return Platform
     * @throws \Throwable
     */
    public static function insert(string $name , string|PlatformInterface $driver , float $deposit_tether = 0): Platform
    {
        if ( $driver instanceof PlatformInterface)
            $driver = $driver::class;

        if ( ! DriverService::isValidDriver($driver))
            throw new InvalidArgumentException("Driver not support!");

        return Platform::insert($name , $driver , $deposit_tether , 0) ;
    }

    /**
     *
     * @param mixed $platform
     * @param string|null $name
     * @param string|PlatformInterface|null $driver
     * @param float|null $deposit_tether
     * @return Platform
     * @throws \Throwable
     */
    public static function edit(mixed $platform , string|null $name = null , string|PlatformInterface|null $driver = null , float|null $deposit_tether = null): Platform
    {
        $platform = self::getPlatform($platform);

        if ( $name == null)
            $name = $platform->name;

        if ( $driver == null)
            $driver = $platform->driver_name;
        elseif ( $driver instanceof PlatformInterface)
            $driver = $driver::class;
        if ( ! DriverService::isValidDriver($driver))
            throw new InvalidArgumentException("Driver not support!");

        if ( $deposit_tether == null)
            $deposit_tether = $platform->deposit_tether;

        return $platform->edit($name , $driver , $deposit_tether , $platform->reserved_tether ) ;
    }

    /**
     *
     * @param mixed $platform
     * @param float|null $amount
     * @return Platform
     * @throws \Throwable
     */
    public static function depositTether(mixed $platform ,  float $amount = null): Platform
    {
        $platform = self::getPlatform($platform);
        $deposit_tether = $platform->deposit_tether + $amount;
        return $platform->edit($platform->name , $platform->driver_name , $deposit_tether , $platform->reserved_tether ) ;
    }


    /**
     *
     * @param mixed $platform
     * @param float|null $amount
     * @return Platform
     * @throws \Throwable
     */
    public static function withdrawTether(mixed $platform ,  float $amount = null): Platform
    {
        $platform = self::getPlatform($platform);
        if ( $amount >  $platform->deposit_tether )
            throw new InvalidArgumentException("Maximum amount of withdraw is :".$platform->deposit_tether );
        $deposit_tether = $platform->deposit_tether - $amount;
        return $platform->edit($platform->name , $platform->driver_name , $deposit_tether , $platform->reserved_tether ) ;
    }

    /**
     *
     * @param mixed $platform
     * @return Platform
     */
    public static function find(mixed $platform): Platform
    {
        return self::getPlatform($platform);
    }

    public static function deactivatePlatform(Platform $platform) : bool
    {
        return $platform->delete() ?? false;
    }

    /**
     * deactivate list of platforms
     * @param array $ids
     * @return bool
     */
    public static function deactivateMultiply(array $ids) : bool
    {
        DB::beginTransaction();
        foreach ($ids as $id)
        {
            $platform = self::find($id);
            self::deactivatePlatform($platform);
        }
        DB::commit();
        return true;
    }

    public static function activatePlatform(Platform $platform) : bool
    {
        return $platform->restore() ?? false;
    }

    /**
     * activate list of platforms
     * @param array $ids
     * @return bool
     */
    public static function activateMultiply(array $ids) : bool
    {
        DB::beginTransaction();
        foreach ($ids as $id)
        {
            $platform = self::find($id);
            self::activatePlatform($platform);
        }
        DB::commit();
        return true;
    }

    public static function listPlatforms() :Collection
    {
        return Platform::query()->orderByDesc('id')->get();
    }

    private static function getPlatform( mixed $platform) : Platform
    {
        if ( $platform instanceof Platform)
            return $platform;
        elseif ( is_object($platform) )
            throw new InvalidArgumentException("Object You send is not valid platform!");
        elseif( (int) $platform > 0 )
            return Platform::findWithId((int) $platform);
        throw new InvalidArgumentException("Object You send is not valid platform!");
    }
}
