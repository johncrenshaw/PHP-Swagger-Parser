<?php
namespace Swagger\Object;

class Properties extends AbstractObject
{
    use CollectionObjectTrait;
    
    protected $_parentSchema = null;

    public function hasParentSchema()
    {
        return isset($this->_parentSchema);
    }
    
    public function getParentSchema()
    {
        return $this->_parentSchema;
    }
    
    public function setParentSchema($parent_schema)
    {
        $this->_parentSchema = $parent_schema;
    }
    
    public function getItem($name)
    {
        return $this->getProperty($name);
    }
    
    public function getProperty($name)
    {
        $ret = $this->getDocumentObjectProperty($name, Items::class);
        if ($ret && $this->hasParentSchema())
        {
            $ret->setParentSchema($this->getParentSchema());
            $ret->setNameInParentSchema($name);
        }
        return $ret;
    }
    
    public function setProperty($name, Items $property)
    {
        return $this->setDocumentObjectProperty($name, $property);
    }
}
