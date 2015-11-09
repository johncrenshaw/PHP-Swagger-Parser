<?php
namespace Swagger\Object;

use Swagger\Json;

trait PropertiesTrait
{
    public function getAllOf()
    {
        return $this->getDocumentObjectProperty('allOf', Schema::class);
    }
    
    public function setAllOf($allOf)
    {
        return $this->setDocumentProperty('allOf', $allOf);
    }

    public function getProperties()
    {
        $ret = $this->getDocumentObjectProperty('properties', Properties::class);
        return $ret;
    }
    
    public function setProperties($properties)
    {
        return $this->setDocumentObjectProperty('properties', $properties);
    }
    
    public function getAllProperties($resolver)
    {
        if ($properties = $this->getProperties())
        {
            $properties = $this->getProperties()->getAll();
        }
        if (!$properties)
        {
            $properties = [];
        }
        if ($this->getAllOf())
        {
            foreach($this->getAllOf() as $schema)
            {
                $schema = $resolver->resolveReference($schema);
                if ($sub_properties = $schema->getAllProperties($resolver))
                {
                    $properties = $this->_mergeProperties($properties, $sub_properties);
                }
            }
        }
        
        return $properties;
    }

    protected function _mergeProperties($a, $b)
    {
        return array_merge($a, $b);
    }
    
    public function getAdditionalProperties()
    {
        return $this->getDocumentProperty('additionalProperties', Schema::class);
    }
    
    public function setAdditionalProperties($additionalProperties)
    {
        return $this->setDocumentProperty('additionalProperties', $additionalProperties);
    }
}
