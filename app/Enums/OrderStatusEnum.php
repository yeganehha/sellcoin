<?php


namespace App\Enums;

use InvalidArgumentException;
use PhpParser\Node\Scalar\String_;

enum OrderStatusEnum:string {
    case Wait = 'wait';
    case Paid = 'paid';
    case Cancel = 'cancel';

    public static function getFromString(string $status):OrderStatusEnum
    {
        $status = ucfirst(strtolower($status));
        $reflection = new \ReflectionEnum(self::class);
        if ( $reflection->hasConstant( $status ) ) {
            return $reflection->getConstant($status);
        } else
            throw new InvalidArgumentException("[{$status}] is not valid status!");
    }

    /**
     * @param OrderStatusEnum $enum
     * @return string
     */
    public static function toString(self $enum):string
    {
        switch ($enum) {
            case self::Wait :
                return 'Wait';
            case self::Paid :
                return 'Paid';
            case self::Cancel :
                return 'Cancel';
        }
    }
}
