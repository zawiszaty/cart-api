<?php

declare(strict_types=1);

namespace App\Module\Cart\UI\HTTP\REST\Request;

use App\Infrastructure\Symfony\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateCartRequest implements RequestDTOInterface
{
    private string $userId;

    public function __construct(Request $request)
    {
        $this->userId = $request->request->get('user_id');
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
