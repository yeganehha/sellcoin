<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property int $deposit_tether
 * @property int $reserved_tether
 * @property string $driver_name
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class Platform extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'deposit_tether',
        'reserved_tether',
        'driver_name',
    ];
    protected $casts = [
        'deposit_tether' => 'int',
        'reserved_tether' => 'int',
    ];
}
