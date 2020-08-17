<?php

/**
 * PHP Domain Parser: Public Suffix List based URL parsing.
 *
 * @see http://github.com/jeremykendall/php-domain-parser for the canonical source repository
 *
 * @copyright Copyright (c) 2017 Jeremy Kendall (http://jeremykendall.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pdp\Storage;

use Pdp\PublicSuffixList;
use Pdp\Rules;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use Throwable;

final class PublicSuffixListCachePsr16Adapter extends JsonPsr16Cache implements PublicSuffixListCache
{
    /**
     * @param mixed $ttl the time to live for the given cache
     */
    public function __construct(CacheInterface $cache, $ttl = null, LoggerInterface $logger = null)
    {
        $this->cache = $cache;
        $this->ttl = $this->setTtl($ttl);
        $this->logger = $logger ?? new NullLogger();
    }

    public function fetchByUri(string $uri): ?Rules
    {
        $cacheData = $this->fetchJson($uri);
        if (null === $cacheData) {
            return null;
        }

        try {
            $rules = Rules::fromJsonString($cacheData);
        } catch (Throwable $exception) {
            $this->forgetJson($uri, $exception);

            return null;
        }

        return $rules;
    }

    public function storeByUri(string $uri, PublicSuffixList $publicSuffixList): bool
    {
        return $this->storeJson($uri, $publicSuffixList);
    }
}
