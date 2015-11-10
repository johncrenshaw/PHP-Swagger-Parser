<?php
namespace Swagger\Object;

use Swagger\Object\Schema as SwaggerSchema;

abstract class Parameter extends AbstractObject implements ReferentialInterface
{
    use TypeObjectTrait,
        ReferentialTrait,
        ReferencableTrait;
    
    public function getName()
    {
        return $this->getDocumentProperty('name');
    }
    
    public function setName($name)
    {
        return $this->setDocumentProperty('name', $name);
    }
    
    public function getIn()
    {
        return $this->getDocumentProperty('in');
    }
    
    public function setIn($in)
    {
        return $this->setDocumentProperty('in', $in);
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
    
    public function getSchema()
    {
        return $this->getDocumentObjectProperty('schema', Schema::class);
    }
    
    public function setSchema(SwaggerSchema $schema)
    {
        return $this->setDocumentObjectProperty('schema', $schema);
    }

    public function getSample($schema_resolver)
    {
        if ($schema = $this->getSchema())
        {
            if ($schema = $schema_resolver->resolveReference($schema))
            {
                return $schema->getSample($schema_resolver);
            }
        }
        
        // Regular parameter
        return $this->_makeSample($schema_resolver, $this->getType(), $this->getFormat(), $this->getItems());
    }
}
