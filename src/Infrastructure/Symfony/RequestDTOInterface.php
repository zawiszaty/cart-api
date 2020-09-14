<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\Request;

interface RequestDTOInterface
{
    public function __construct(Request $request);
}
