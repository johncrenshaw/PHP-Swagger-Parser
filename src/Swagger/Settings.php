<?php
namespace Swagger;

class Settings extends SwaggerObject\AbstractObject
{
    static protected $throwMissingDocumentPropertyException = true;
    
    public function getThrowMissingDocumentPropertyException()
    {
        return self::$throwMissingDocumentPropertyException;
    }
    
    public function setThrowMissingDocumentPropertyException($throw = true)
    {
		self::$throwMissingDocumentPropertyException = $throw;
    }
}
