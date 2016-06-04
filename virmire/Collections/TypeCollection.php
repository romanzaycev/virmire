<?php declare(strict_types = 1);

namespace Virmire\Collections;

/**
 * Class TypeCollection
 *
 * @package Virmire\Collections
 */
class TypeCollection extends Collection
{
    
    /**
     * @var string
     */
    private $type;
    
    /**
     * TypeCollection constructor.
     *
     * @param string $type
     * @param array $data
     */
    public function __construct(string $type, array $data = [])
    {
        $this->type = $type;
        
        foreach ($data as $k => $v) {
            $this->addItem($k, $v);
        }
        
        parent::__construct();
    }
    
    /**
     * @param string|int $key
     * @param mixed $object
     *
     * @throws Exceptions\CollectionKeyHasUseException
     * @throws \TypeError
     */
    public function addItem($key, $object)
    {
        $type = get_class($object);
        
        if ($type !== $this->type) {
            throw new \TypeError(sprintf('Сollection type "%s" does not match argument type: "%s"', $this->type,
                $type));
        }
        
        parent::addItem($key, $object);
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}