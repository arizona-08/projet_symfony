<?php

namespace App\DataFixtures;

use Faker\Provider\FakeCar;
use Faker\Generator;

class FakeCarProvider extends FakeCar
{
    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }
}
