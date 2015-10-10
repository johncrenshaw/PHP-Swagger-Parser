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
    
    public function setSchema(Schema $schema)
    {
        return $this->setDocumentProperty('schema', $schema);
    }
}
