<?php
/**
 * ...
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelloWorldController.
 *
 * @Route(
 *     "/hello-world"
 * )
 */

class HelloWorldController extends AbstractController
{
    /**
     *  Index action.
     *
     * @param string|null $name Input string
     * @return Response HTTP Response
     *
     * @Route(
     *     "/{name}",
     *     methods={"GET"},
     *     name="hello-world_index",
     *     defaults={"name": "World"},
     *     requirements={"name": "[a-zA-Z1-9]+"}
     * )
     */
    public function index(?string $name = null): Response
    {
        return $this->render(
            'hello-world/index.html.twig',
            ['name' => $name]
        );
    }

}