<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Vacancy\Provider\Journal;

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
    private $templateEngine;

    /**
     * Provides vacancies by a simple abstract concept of journal with pages, using pagination internally
     *
     * @var Journal
     */
    private $vacancyJournal;

    /**
     * VacancyController constructor.
     *
     * @param EngineInterface $templateEngine Template engine
     * @param Journal         $vacancyJournal Provides vacancies by a simple abstract concept of journal with pages
     */
    public function __construct(EngineInterface $templateEngine, Journal $vacancyJournal)
    {
        $this->templateEngine = $templateEngine;
        $this->vacancyJournal = $vacancyJournal;
    }

    /**
     * Renders a list of vacancies
     *
     * @param int $page Page in vacancy journal
     *
     * @return Response
     */
    public function listAction(int $page): Response
    {
        $pagination = $this->vacancyJournal->read($page);
        $pagination->setUsedRoute('anthill_vacancy_list');

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/list.html.twig',
            [
                'pagination'         => $pagination,
                'route_vacancy_show' => 'anthill_vacancy_show',
            ]
        );

        $response = new Response($content);

        return $response;
    }

    /**
     * Renders a vacancy show page
     *
     * @param string $slug SEO-friendly vacancy identifier
     *
     * @return Response
     */
    public function showAction(string $slug): Response
    {
        $vacancy = $this->vacancyJournal->find($slug);

        if (!$vacancy instanceof Vacancy) {
            throw new NotFoundHttpException();
        }

        $content  = $this->templateEngine->render('@VesloAnthill/Vacancy/show.html.twig', ['vacancy' => $vacancy]);
        $response = new Response($content);

        return $response;
    }
}
