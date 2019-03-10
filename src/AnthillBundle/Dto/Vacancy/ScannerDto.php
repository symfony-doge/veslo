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
