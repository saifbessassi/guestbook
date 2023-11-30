<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return new Response(<<<EOF
            <html>
                <body>
                    <img src="/images/UnderConstruction.gif" />
                </body>
            </html>
            EOF
        );
    }
}
