<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Vacancy controller.
 */
class VacancyController
{
    /**
     * Template engine
     *
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * VacancyController constructor.
     *
     * @param EngineInterface $templateEngine Template engine
     */
    public function __construct(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Renders vacancy list
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $content = $this->templateEngine->render(':default:index.html.twig');

        $response = new Response($content);

        return $response;
    }

    public function viewAction()
    {
    }
}
