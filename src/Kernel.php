<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
