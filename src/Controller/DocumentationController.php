<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends BaseController
{
    /**
     * @Route("/documentation", name="documentation", methods={"GET"})
     * @param Request $request
     * @return null|object
     */
    public function indexAction(Request $request)
    {
        return $this->render("documentation/index.html.twig",[
            'page' => $request->get("page", "swagger")
        ]);
    }

    /**
     * @Route("/documentation/{name}.json", name="swagger")
     * @param string $name
     * @return null|object
     */
    public function swaggerAction(string $name)
    {
        return $this->render("documentation/{$name}.json");
    }
}