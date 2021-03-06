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
 * Context of scanner for vacancy URL that supports multiple parsing strategies
 */
// inheritance used only for symfony serializer compatibility and cleaner communication with external services.
class MultistrategicScannerDto extends ScannerDto
{
    // TODO
}
