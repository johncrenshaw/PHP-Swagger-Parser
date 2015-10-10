<?php
namespace Swagger\Object;

class Items extends AbstractObject implements TypeObjectInterface, ReferentialInterface
{
    use TypeObjectTrait,
        ReferentialTrait,
        PropertiesTrait;
    
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
    
    public function getTitle()
    {
        return $this->getDocumentProperty('title');
    }
    
    public function setTitle($title)
    {
        return $this->setDocumentProperty('title', $title);
    }
    
    public function getDescription()
    {
        return $this->getDocumentProperty('description');
    }
    
    public function setDescription($description)
    {
        return $this->setDocumentProperty('description', $description);
    }
    
    public function getRequired()
    {
        return $this->getDocumentProperty('required');
    }
    
    public function setRequired($required)
    {
        return $this->setDocumentProperty('required', $required);
    }
    
    public function getMaxProperties()
    {
        return $this->getDocumentProperty('maxProperties');
    }
    
    public function setMaxProperties($maxProperties)
    {
        return $this->setDocumentProperty('maxProperties', $maxProperties);
    }
    
    public function getMinProperties()
    {
        return $this->getDocumentProperty('minProperties');
    }
    
    public function setMinProperties($minProperties)
    {
        return $this->setDocumentProperty('minProperties', $minProperties);
    }
}
