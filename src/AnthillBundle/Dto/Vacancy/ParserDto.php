<?php

namespace Veslo\AnthillBundle\Dto\Vacancy;

/**
 * Context of parser for vacancy URL
 */
class ParserDto
{
    /**
     * Parser name
     *
     * @var string
     */
    private $name;

    /**
     * Sets parser name
     *
     * @param string $name Parser name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns parser name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
