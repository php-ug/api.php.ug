<?php
/**
 * Copyright (c)2014-2014 heiglandreas
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIBILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category 
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2014-2014 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     07.02.14
 * @link      https://github.com/heiglandreas/
 */

namespace Phpug\Api\Cache;

use Doctrine\ORM\EntityManager;
use Phpug\Api\Entity\Usergroup;
use Phpug\Api\Entity\Cache as CacheEntity;
use DateTimeImmutable;
use DateInterval;
use Psr\Container\ContainerInterface;

class Cache implements CacheInterface
{
    private $usergroup;

    private $type = 'country';

    private $container;

    private $populator;

    public function __construct(Usergroup $usergroup = null, ContainerInterface $container = null, CachePopulatorInterface $populator = null)
    {
        if ($usergroup) {
            $this->setUsergroup($usergroup);
        }
        if ($container) {
            $this->setServiceManager($container);
        }
        if ($populator) {
            $this->setPopulator($populator);
        }
    }

    public function setUsergroup(Usergroup $usergroup) : self
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    public function setServiceManager(ContainerInterface $container) : self
    {
        $this->container = $container;

        return $this;
    }

    public function setPopulator(CachePopulatorInterface $populator) : self
    {
        $this->populator = $populator;

        return $this;
    }

    /**
     * Get the cache-value
     *
     * @param string $type
     *
     * @return Cache
     */
    public function getCache() : CacheEntity
    {
        $caches = $this->usergroup->getCaches();
        $myCache = null;
        foreach($caches as $cache) {
            if ($this->type != $cache->getType()) {
                continue;
            }
            $myCache = $cache;
            break;
        }

        if (! $myCache) {
            $myCache = $this->container->get(CacheEntity::class);
            $myCache->setType($this->type);
            $this->usergroup->caches->add($myCache);
            $myCache->setGroup($this->usergroup);
        }

        $config = $this->container->get('config');
        $cacheLifeTime = $config['phpug']['entity']['cache'][$this->type]['cacheLifeTime'];
        $cacheLifeTime = new DateInterval($cacheLifeTime);
        if ($myCache->getLastChangeDate()->add($cacheLifeTime) < new DateTimeImmutable() || trim($myCache->getCache()) == '') {
            $value = $this->populator->populate($this->usergroup, $this->container);
            if ($value) {
                $myCache->setCache($value);
            }
            $myCache->setLastChangeDate(new DateTimeImmutable());
            $myCache = $this->makePersistent($myCache);

        }
        return $myCache;
    }

    /**
     * Make the cached data persistent
     *
     * @param CacheEntity $cache
     *
     * @return CacheEntity
     */
    protected function makePersistent(CacheEntity $cache) : CacheEntity
    {

        $cache->setLastChangeDate(new DateTimeImmutable());
        $em = $this->container->get(EntityManager::class);
        $em->persist($cache);
        $em->flush();

        return $cache;
    }
}
