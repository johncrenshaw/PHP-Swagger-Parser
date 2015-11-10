<?php
namespace Swagger\Object\Parameter;

use Swagger\Object\Parameter as AbstractParameter;
use Swagger\Object\Schema as SwaggerSchema;

class Body extends AbstractParameter
{
    public function getSchema()
    {
        return $this->getDocumentObjectProperty('schema', SwaggerSchema::class);
    }
    
    public function setSchema(SwaggerSchema $schema)
    {
        return $this->setDocumentObjectProperty('schema', $schema);
    }

    public function getSample($schema_resolver)
    {
        return $this->_makeSample($schema_resolver, $this->getType(), $this->getFormat(), $this->getItems());
    }
}
