<?php
namespace Swagger\Object;

use Swagger\Exception as SwaggerException;
use Swagger\Settings as SwaggerSettings;
use stdClass;

abstract class AbstractObject implements ObjectInterface
{
    protected $document;
    
    public function __construct($document) {
        $this->setDocument($document);
    }
    
    public function getVendorExtension($extension, $class = null)
    {
        if(is_string($class)) {
            return $this->getDocumentObjectProperty("x-{$extension}", $class);
        } else {
            return $this->getDocumentProperty("x-{$extension}");
        }
    }
    
    public function setVendorExtension($extension, $value)
    {
        if($value instanceof SwaggerObject\ObjectInterface || (is_array($value) && reset($value) instanceof SwaggerObject\ObjectInterface)) {
            return $this->setDocumentObjectProperty("x-{$extension}", $value);
        } else {
            return $this->setDocumentProperty("x-{$extension}", $value);
        }
    }
    
    public function getDocument() {
        if(!($this->document instanceof stdClass)) {
            $this->document = new stdClass;
        }
        return $this->document;
    }
    
    public function setDocument($document) {
        SwaggerException\InvalidSourceDocumentException::assess($document);
        
        $this->document = $document;
        return $this;
    }
    
    public function hasDocumentProperty($name) {
		return isset($this->getDocument()->$name);
    }
    
    public function getDocumentProperty($name) {
		if (SwaggerSettings::getThrowMissingDocumentPropertyException())
		{
			SwaggerException\MissingDocumentPropertyException::assess($this->getDocument(), $name);
		}
		else if (!isset($this->getDocument()->$name))
		{
			return null;
		}
        
        return $this->getDocument()->$name;
    }
    
    public function setDocumentProperty($name, $value) {
        $this->getDocument()->$name = $value;
        
        return $this;
    }
    
    public function unsetDocumentProperty($name) {
        unset($this->getDocument()->{$name});
    }
    
    public function hasDocumentObjectProperty($name)
    {
		return $this->hasDocumentProperty($name);
	}

    public function getDocumentObjectProperty($name, $swaggerObjectClass)
    {
        $value = $this->getDocumentProperty($name);
        
        if(is_array($value)) {
            $newValue = [];
            
            foreach($value as $key => $arrayValue) {
                $newValue[$key] = new $swaggerObjectClass($arrayValue);
            }
            
            return $newValue;
        } else {
            if (is_null($value))
            {
                return null;
            }
            return new $swaggerObjectClass($value);
        }
    }
    
    public function setDocumentObjectProperty($name, $object)
    {
        if(is_array($object)) {
            $value = [];
            
            foreach($object as $arrayObject) {
                $value[] = $object->getSwaggerObjectValue();
            }
        } else {
            $value = $object->getSwaggerObjectValue();
        }
        
        return $this->setDocumentProperty($name, $value);
    }
    
    public function unsetDocumentObjectProperty($name)
    {
		return $this->unsetDocumentProperty($name);
	}

    public function getDocumentParameterProperty($name)
    {
        $value = $this->getDocumentProperty($name);
        
        if(is_array($value)) {
            $newValue = [];
            
            foreach($value as $key => $arrayValue) {
                $swaggerObjectClass = $this->getParameterClass($arrayValue);
                $newValue[$key] = new $swaggerObjectClass($arrayValue);
            }
            
            return $newValue;
        } else {
            if (is_null($value))
            {
                return null;
            }
            $swaggerObjectClass = $this->getParameterClass($value);
            return new $swaggerObjectClass($value);
        }
    }

    public function getParameterClass($value)
    {
        if (isset($value->in))
        {
            switch($value->in)
            {
            case 'body':
                return Parameter\Body::class;
            case 'formData':
                return Parameter\FormData::class;
            case 'header':
                return Parameter\Header::class;
            case 'path':
                return Parameter\Path::class;
            case 'query':
                return Parameter\Query::class;
            }
        }

        // Parameter location not indicated or not a known value
        return Parameter\Generic::class;
    }
}
