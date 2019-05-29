<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\AnthillBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AnthillBundle\Enum\Route;
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
    public function list(int $page): Response
    {
        $pagination = $this->vacancyJournal->read($page);

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/list.html.twig',
            [
                'pagination'         => $pagination,
                'route_vacancy_show' => Route::VACANCY_SHOW,
            ]
        );

        $response = new Response($content);

        return $response;
    }

    /**
     * Renders a list of vacancies for a target category
     *
     * @param Category $category Vacancy category
     * @param int      $page     Page in vacancy journal
     *
     * @return Response
     *
     * @ParamConverter(name="category", converter="doctrine.orm", options={"mapping"={"categoryName": "name"}})
     */
    public function listByCategory(Category $category, int $page): Response
    {
        $pagination = $this->vacancyJournal->readCategory($category, $page);

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/list.html.twig',
            [
                'pagination'         => $pagination,
                'route_vacancy_show' => Route::VACANCY_SHOW,
            ]
        );

        $response = new Response($content);

        return $response;
    }

    /**
     * Renders a vacancy show page
     *
     * @param Vacancy $vacancy Vacancy entity
     *
     * @return Response
     *
     * @ParamConverter(name="vacancy", converter="doctrine.orm", options={"mapping"={"vacancySlug": "slug"}})
     */
    public function show(Vacancy $vacancy): Response
    {
        $content  = $this->templateEngine->render('@VesloAnthill/Vacancy/show.html.twig', ['vacancy' => $vacancy]);
        $response = new Response($content);

        return $response;
    }
}
