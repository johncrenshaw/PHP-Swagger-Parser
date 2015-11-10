<?php
namespace Swagger\Object;

class Items extends AbstractObject implements TypeObjectInterface, ReferentialInterface
{
    use TypeObjectTrait,
        ReferentialTrait,
        PropertiesTrait;

    protected $_parentSchema = null;
    protected $_nameInParentSchema = null;

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
    
    public function hasNameInParentSchema()
    {
        return isset($this->_nameInParentSchema);
    }
    
    public function getNameInParentSchema()
    {
        return $this->_nameInParentSchema;
    }
    
    public function setNameInParentSchema($name_in_parent_schema)
    {
        $this->_nameInParentSchema = $name_in_parent_schema;
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
        // Search the parent schema to see if this item is required. This is the "right" way.
        if ($parent = $this->getParentSchema())
        {
            if (is_array($required = $parent->getRequired()))
            {
                return in_array($this->getNameInParentSchema(), $required);
            }
        }

        // Non-standard addition, because the "right" way is awkward and inconsistent
        return $this->getDocumentProperty('required');
    }
    
    public function setRequired($required)
    {
        // Add to the "reqired" list in the parent schema. This is the "right" way.
        if ($parent = $this->getParentSchema())
        {
            if (!is_array($required = $parent->getRequired()))
            {
                $required = [];
            }
            if (!in_array($this->getNameInParentSchema(), $required))
            {
                $required[] = $this->getNameInParentSchema();
            }
            $parent->setRequired($required);
        }

        // Non-standard addition, because the "right" way is awkward and inconsistent
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
    
    public function getSample($schema_resolver)
    {
        return $this->_makeSample($schema_resolver, $this->getType(), $this->getFormat(), $this->getItems());
    }
}
