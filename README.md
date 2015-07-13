```php
    'doctrine-hydrator' => array(
        'DbApi\\V1\\Rest\\Artist\\ArtistHydrator' => array(
            'entity_class' => 'Db\\Entity\\Artist',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => array(
                'album' => 'hydrator_strategy_include_album',
            ),
            'use_generated_hydrator' => true,
            'filters' => array(
                'exclude_name' => array(
                    'condition' => 'and',
                    'filter' => 'hydrator_filter_exclude_name',
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
            'hydrator_filter_exclude_name' => 'DbApi\\Hydrator\\Filter\\ExcludeName',
            'hydrator_strategy_include_album' => 'DbApi\\Hydrator\\Strategy\\Artist\\Album',
        ),
    ),
```

```php
namespace DbApi\Hydrator\Filter;

use Zend\Stdlib\Hydrator\Filter\FilterInterface;

class ExcludeName implements FilterInterface
{
    public function filter($field)
    {
        if ($field == 'name') {
            return false;
        }

        return true;
    }
}
```

```php
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
```
