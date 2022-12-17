<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;


/**
 * @property string $name
 * @property float $deposit_tether
 * @property float $reserved_tether
 * @property string $driver_name
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Collection orders
 *
 */
class Platform extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'deposit_tether',
        'reserved_tether',
        'driver_name',
    ];
    protected $casts = [
        'deposit_tether' => 'float',
        'reserved_tether' => 'float',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class)->orderByDesc('id');
    }

    /**
     * @param int $id
     * @return static
     */
    public static function findWithId(int $id): self
    {
        return self::withTrashed()->findOrFail($id);
    }

    /**
     * @param string $name
     * @param string $driver_name
     * @param float $deposit_tether
     * @param float $reserved_tether
     * @return Platform
     * @throws \Throwable
     */
    public static function insert(string $name , string $driver_name , float $deposit_tether = 0 , float $reserved_tether = 0 ): self
    {
        $platform = new self();
        $platform->edit($name, $driver_name, $deposit_tether, $reserved_tether);
        return $platform;
    }

    /**
     * @param string $name
     * @param string $driver_name
     * @param float $deposit_tether
     * @param float $reserved_tether
     * @return Platform
     * @throws \Throwable
     */
    public function edit(string $name , string $driver_name , float $deposit_tether , float $reserved_tether ): self
    {
        $this->name = $name ;
        $this->driver_name = $driver_name ;
        $this->deposit_tether = $deposit_tether ;
        $this->reserved_tether = $reserved_tether ;
        $this->saveOrFail();
        return $this;
    }

    /**
     * @param float $amount
     * @param bool $isReserved
     * @return Platform
     * @throws \Throwable
     */
    public function updateTether(float $amount , bool $isReserved = true): self
    {
        if ( $isReserved ) {
            $this->deposit_tether -= $amount;
            $this->reserved_tether += $amount;
        } else {
            $this->deposit_tether += $amount;
            $this->reserved_tether -= $amount;
        }
        $this->saveOrFail();
        return $this;
    }
}
