<?php

/**
 * PHP Domain Parser: Public Suffix List based URL parsing.
 *
 * @see http://github.com/jeremykendall/php-domain-parser for the canonical source repository
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pdp\Storage;

use Pdp\RootZoneDatabase;
use Pdp\UnableToLoadRootZoneDatabase;

interface RootZoneDatabaseClient
{
    /**
     * @throws UnableToLoadRootZoneDatabase
     */
    public function get(string $uri): RootZoneDatabase;
}
