<?php

namespace App\DataFixtures;

class MotorcycleProvider{
    private static $brands = [
        'Yamaha' => ['MT-07', 'YZF-R1', 'XSR900', 'Tracer 900'],
        'Honda' => ['CBR600RR', 'Gold Wing', 'Africa Twin', 'CB500X'],
        'Kawasaki' => ['Ninja ZX-6R', 'Versys 650', 'Z900', 'KX450'],
        'Suzuki' => ['Hayabusa', 'GSX-R750', 'V-Strom 650', 'Katana'],
        'Ducati' => ['Panigale V4', 'Monster 821', 'Diavel', 'Scrambler'],
        'Harley-Davidson' => ['Sportster', 'Street Glide', 'Fat Bob', 'Iron 883'],
    ];

    public static function motorcycleBrand()
    {
        return array_rand(self::$brands); // Random brand name
    }

    public static function motorcycleModel($brand)
    {
        return self::$brands[$brand][array_rand(self::$brands[$brand])]; // Matching model
    }
}