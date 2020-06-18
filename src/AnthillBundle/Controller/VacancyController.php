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
use Symfony\Component\Translation\TranslatorInterface;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AnthillBundle\Enum\Route;
use Veslo\AnthillBundle\Vacancy\Provider\Archive;
use Veslo\AnthillBundle\Vacancy\Provider\Journal;
use Veslo\SanityBundle\Entity\Repository\Vacancy\IndexRepository;

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
     * Provides archived vacancies, uses pagination internally
     *
     * @var Archive
     */
    private $vacancyArchive;

    /**
     * Sanity index repository
     *
     * @var IndexRepository
     */
    private $indexRepository;

    /**
     * Translates the given message using locale
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * VacancyController constructor.
     *
     * @param EngineInterface     $templateEngine  Template engine
     * @param Journal             $vacancyJournal  Provides vacancies by an abstract concept of journal
     * @param Archive             $vacancyArchive  Provides archived vacancies, uses pagination internally
     * @param IndexRepository     $indexRepository Sanity index repository
     * @param TranslatorInterface $translator      Translates the given message using locale
     */
    public function __construct(
        EngineInterface $templateEngine,
        Journal $vacancyJournal,
        Archive $vacancyArchive,
        IndexRepository $indexRepository,
        TranslatorInterface $translator
    ) {
        $this->templateEngine  = $templateEngine;
        $this->vacancyJournal  = $vacancyJournal;
        $this->vacancyArchive  = $vacancyArchive;
        $this->indexRepository = $indexRepository;
        $this->translator      = $translator;
    }

    /**
     * Renders a list of vacancies
     *
     * @param int $page Page in the vacancy journal
     *
     * @return Response
     */
    public function list(int $page): Response
    {
        $pagination = $this->vacancyJournal->read($page);

        $messageVacanciesNotFound = $this->translator->trans('fresh_vacancies_are_not_found');

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/list.html.twig',
            [
                'pagination'                     => $pagination,
                'route_vacancy_show'             => Route::VACANCY_SHOW,
                'route_vacancy_list_by_category' => Route::VACANCY_LIST_BY_CATEGORY,
                'messages'                       => [
                    'vacancies_not_found' => $messageVacanciesNotFound,
                ],
            ]
        );

        $response = new Response($content);

        return $response;
    }

    /**
     * Renders a list of vacancies for a target category
     *
     * @param Category $category Vacancy category
     * @param int      $page     Page in the vacancy journal
     *
     * @return Response
     *
     * @ParamConverter(name="category", converter="doctrine.orm", options={"mapping"={"categoryName": "name"}})
     */
    public function listByCategory(Category $category, int $page): Response
    {
        $pagination = $this->vacancyJournal->readCategory($category, $page);

        $messageVacanciesNotFound = $this->translator->trans('fresh_vacancies_are_not_found');

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/list.html.twig',
            [
                'pagination'                     => $pagination,
                'route_vacancy_show'             => Route::VACANCY_SHOW,
                'route_vacancy_list_by_category' => Route::VACANCY_LIST_BY_CATEGORY,
                'messages'                       => [
                    'vacancies_not_found' => $messageVacanciesNotFound,
                ],
            ]
        );

        $response = new Response($content);

        return $response;
    }

    /**
     * Renders a list of archived vacancies
     *
     * @param int $page Page in the vacancy archive
     *
     * @return Response
     */
    public function listArchive(int $page): Response
    {
        $pagination = $this->vacancyArchive->read($page);

        $messageVacanciesNotFound = $this->translator->trans('vacancies_are_not_found');

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/list.html.twig',
            [
                'pagination'                     => $pagination,
                'route_vacancy_show'             => Route::VACANCY_SHOW,
                'route_vacancy_list_by_category' => Route::VACANCY_LIST_BY_CATEGORY,
                'messages'                       => [
                    'vacancies_not_found' => $messageVacanciesNotFound,
                ],
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
        $vacancyId   = $vacancy->getId();
        $sanityIndex = $this->indexRepository->findByVacancyId($vacancyId);

        $content = $this->templateEngine->render(
            '@VesloAnthill/Vacancy/show.html.twig',
            [
                'vacancy'                        => $vacancy,
                'sanity_index'                   => $sanityIndex,
                'route_vacancy_list_by_category' => Route::VACANCY_LIST_BY_CATEGORY,
            ]
        );

        $response = new Response($content);

        return $response;
    }
}
