<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain;

interface ProductFinder
{
    public function listAll(int $page, int $limit): array;
}
