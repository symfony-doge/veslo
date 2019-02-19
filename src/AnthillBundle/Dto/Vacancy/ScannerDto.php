<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy;

/**
 * Context of scanner for vacancy URL
 */
class ScannerDto
{
    /**
     * Scanner name
     *
     * @var string
     */
    private $name;

    /**
     * Sets scanner name
     *
     * @param string $name Scanner name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns scanner name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
