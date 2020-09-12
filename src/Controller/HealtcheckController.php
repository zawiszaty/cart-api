<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HealtcheckController extends AbstractController
{
    /**
     * @Route("/healtcheck", name="get_employees", methods={"GET"})
     */
    public function testAction(): Response
    {
        return new Response('healtcheck');
    }
}
