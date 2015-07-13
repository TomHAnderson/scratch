<?php

namespace DbApi\Hydrator\Strategy\Artist;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use DoctrineModule\Stdlib\Hydrator\Strategy\AbstractCollectionStrategy;
use ZF\Hal\Collection;

/**
 * Class CollectionExtract
 * A field-specific hydrator for collections.
 *
 * @returns HalCollection
 */
class Album extends AbstractCollectionStrategy implements StrategyInterface
{
    public function extract($value)
    {
        $value = ($value) ?: array();
        $halCollection = new Collection($value);

        return $halCollection;
    }

    public function hydrate($value)
    {
        $artistAlbums = $this->getObject()->getAlbum();

        // Remove all albums then add
        foreach ($artistAlbums as $album) {
            $this->getObject()->removeAlbum($album);
            $album->removeartist($this->getObject());
        }

        foreach ($value as $album) {
            $album->addartist($this->getObject());
            $this->getObject()->addAlbum($album);
        }
    }
}
