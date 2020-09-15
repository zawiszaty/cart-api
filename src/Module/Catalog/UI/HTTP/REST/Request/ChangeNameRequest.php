<?php

declare(strict_types=1);

namespace App\Module\Catalog\UI\HTTP\REST\Request;

use App\Infrastructure\Symfony\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;

final class ChangeNameRequest implements RequestDTOInterface
{
    private string $name;

    public function __construct(Request $request)
    {
        $this->name = $request->request->get('name');
    }

    public function getName(): string
    {
        return $this->name;
    }
}
