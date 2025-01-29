<?php

namespace App\Tests;

use App\Other\EasterEgg;
use PHPUnit\Framework\TestCase;

class EasterEggTest extends TestCase
{
    public function testEasterEggIsString(): void
    {
        $easterEgg = new EasterEgg();
        $phrase = $easterEgg->getPhrase();
        $this->assertIsString($phrase, "The phrase is not a string");
    }
}
