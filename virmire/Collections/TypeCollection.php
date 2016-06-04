<?php declare(strict_types = 1);

namespace Virmire\Collections;

/**
 * Class TypeCollection
 *
 * @package Virmire\Collections
 */
class TypeCollection extends AbstractCollection
{
    
    /**
     * @var string
     */
    private $type;
    
    /**
     * TypeCollection constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct();
        
        $this->type = $type;
    }
    
    public function addItem($key, $object)
    {
        $type = get_class($object);
        
        if (get_class($object) !== $this->type) {
            throw new \TypeError(sprintf('Сollection type "%s" does not match argument type: "%s"', $this->type,
                $type));
        }
        
        parent::addItem($key, $object);
    }
    
}