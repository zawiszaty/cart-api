<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\ListProductQuery;

use App\Module\Catalog\Domain\ProductFinder as ProductFinderInterface;

final class ListProductQuery
{
    private ProductFinderInterface $finder;

    public function __construct(ProductFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    public function list(int $page = 1, int $limit = 3): array
    {
        return $this->finder->listAll($page, $limit);
    }
}
