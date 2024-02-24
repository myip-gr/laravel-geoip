<?php

declare(strict_types=1);

namespace InteractionDesignFoundation\GeoIP;

use Illuminate\Cache\CacheManager;

/**
 * @psalm-import-type LocationArray from \InteractionDesignFoundation\GeoIP\Location
 */
class Cache
{
    /**
     * Instance of cache manager.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Lifetime of the cache.
     *
     * @var int
     */
    protected $expires;

    /** Cache prefix */
    protected string $prefix = '';

    /**
     * Create a new cache instance.
     *
     * @param CacheManager $cache
     * @param array $tags
     * @param int $expires
     */
    public function __construct(CacheManager $cache, $tags, $expires = 30)
    {
        $this->cache = $tags ? $cache->tags($tags) : $cache;
        $this->expires = $expires;
    }

    /**
     * @internal A hack to support prefixes. Added as a setter to avoid BC breaks.
     * @deprecated Will be removed in v2.0
     */
    public function setPrefix(?string $prefix = null): void
    {
        $this->prefix = (string) $prefix;
    }

    /**
     * Get an item from the cache.
     *
     * @param string $name
     *
     * @return Location|null
     */
    public function get($name)
    {
        /** @psalm-var LocationArray|null $value */
        $value = $this->cache->get($this->prefix . $name);

        return is_array($value)
            ? new Location($value)
            : null;
    }

    /**
     * Store an item in cache.
     *
     * @param string $name
     * @param Location $location
     *
     * @return bool
     */
    public function set($name, Location $location)
    {
        return $this->cache->put($this->prefix . $name, $location->toArray(), $this->expires);
    }

    /**
     * Flush cache for tags.
     *
     * @return bool
     */
    public function flush()
    {
        return $this->cache->flush();
    }
}
