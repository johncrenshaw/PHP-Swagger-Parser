<?php
namespace Swagger;

class Settings extends SwaggerObject\AbstractObject
{
    static protected $throwMissingDocumentPropertyException = true;
    
    public static function getThrowMissingDocumentPropertyException()
    {
        return self::$throwMissingDocumentPropertyException;
    }
    
    public static function setThrowMissingDocumentPropertyException($throw = true)
    {
		self::$throwMissingDocumentPropertyException = $throw;
    }
}
