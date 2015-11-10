<?php
namespace Swagger\Object;

trait TypeObjectTrait
{
    public function getType()
    {
        return $this->getDocumentProperty('type');
    }
    
    public function setType($type)
    {
        return $this->setDocumentProperty('type', $type);
    }
    
    public function getFormat()
    {
        return $this->getDocumentProperty('format');
    }
    
    public function setFormat($format)
    {
        return $this->setDocumentProperty('format', $format);
    }
    
    public function getItems()
    {
        return $this->getDocumentObjectProperty('items', Items::class);
    }
    
    public function setItems(Items $items)
    {
        return $this->setDocumentObjectProperty('items', $items);
    }
    
    public function getCollectionFormat()
    {
        return $this->getDocumentProperty('collectionFormat');
    }
    
    public function setCollectionFormat($collectionFormat)
    {
        return $this->setDocumentProperty('collectionFormat', $collectionFormat);
    }
    
    public function getDefault()
    {
        return $this->getDocumentProperty('default');
    }
    
    public function setDefault($default)
    {
        return $this->setDocumentProperty('default', $default);
    }
    
    public function getMaximum()
    {
        return $this->getDocumentProperty('maximum');
    }
    
    public function setMaximum($maximum)
    {
        return $this->setDocumentProperty('maximum', $maximum);
    }
    
    public function getExclusiveMaximum()
    {
        return $this->getDocumentProperty('exclusiveMaximum');
    }
    
    public function setExclusiveMaximum($exclusiveMaximum)
    {
        return $this->setDocumentProperty('exclusiveMaximum', $exclusiveMaximum);
    }
    
    public function getMinimum()
    {
        return $this->getDocumentProperty('minimum');
    }
    
    public function setMinimum($minimum)
    {
        return $this->setDocumentProperty('minimum', $minimum);
    }
    
    public function getExclusiveMinimum()
    {
        return $this->getDocumentProperty('exclusiveMinimum');
    }
    
    public function setExclusiveMinimum($exclusiveMinimum)
    {
        return $this->setDocumentProperty('exclusiveMinimum', $exclusiveMinimum);
    }
    
    public function getMaxLength()
    {
        return $this->getDocumentProperty('maxLength');
    }
    
    public function setMaxLength($maxLength)
    {
        return $this->setDocumentProperty('maxLength', $maxLength);
    }
    
    public function getMinLength()
    {
        return $this->getDocumentProperty('minLength');
    }
    
    public function setMinLength($minLength)
    {
        return $this->setDocumentProperty('minLength', $minLength);
    }
    
    public function getPattern()
    {
        return $this->getDocumentProperty('pattern');
    }
    
    public function setPattern($pattern)
    {
        return $this->setDocumentProperty('pattern', $pattern);
    }
    
    public function getMaxItems()
    {
        return $this->getDocumentProperty('maxItems');
    }
    
    public function setMaxItems($maxItems)
    {
        return $this->setDocumentProperty('maxItems', $maxItems);
    }
    
    public function getMinItems()
    {
        return $this->getDocumentProperty('minItems');
    }
    
    public function setMinItems($minItems)
    {
        return $this->setDocumentProperty('minItems', $minItems);
    }
    
    public function getUniqueItems()
    {
        return $this->getDocumentProperty('uniqueItems');
    }
    
    public function setUniqueItems($uniqueItems)
    {
        return $this->setDocumentProperty('uniqueItems', $uniqueItems);
    }
    
    public function getEnum()
    {
        return $this->getDocumentProperty('enum');
    }
    
    public function setEnum($enum)
    {
        return $this->setDocumentProperty('enum', $enum);
    }
    
    public function getMultipleOf()
    {
        return $this->getDocumentProperty('multipleOf');
    }
    
    public function setMultipleOf($multipleOf)
    {
        return $this->setDocumentProperty('multipleOf', $multipleOf);
    }

    public function _makeSample($schema_resolver, $type, $format, $items)
    {
        // If we have an explicit sample, use it
        if (($sample = $this->getVendorExtension('sample')) !== null)
        {
            return $sample;
        }

        // If we have a default, use it
        if (($default = $this->getDefault()) !== null)
        {
            return $default;
        }

        // If we have an enum, use the first entry
        if ($enum = $this->getEnum())
        {
            if (count($enum) && isset($enum[0]))
            {
                return $enum[0];
            }
        }
        
        switch($type)
        {
        case 'object':
            $ret = [];
            foreach($this->getAllProperties($schema_resolver) as $property_name=>$property)
            {
                $property = $schema_resolver->resolveReference($property);
                $ret[$property_name] = $property->getSample($schema_resolver);
            }
            if ($additional_properties = $this->getAdditionalProperties()) {
                $additional_properties = $schema_resolver->resolveReference($additional_properties);
                $ret['foo'] = $additional_properties->getSample($schema_resolver);
                $ret['...'] = '...';
            }
            return $ret;
            break;
        case 'array':
            $ret = [];
            $items = $schema_resolver->resolveReference($items);
            $ret[] = $items->getSample($schema_resolver);
            $ret[] = '...';
            return $ret;
            break;
        case 'integer':
            if ($this->getMinimum() !== null && $this->getMaximum() !== null)
            {
                return (int)(($this->getMinimum() + $this->getMaximum()) / 2);
            }
            else if ($this->getMinimum() !== null)
            {
                return $this->getMinimum();
            }
            else if ($this->getMaximum() !== null)
            {
                return $this->getMaximum();
            }
            return 1234567890;
            break;
        case 'number':
            if ($this->getMinimum() !== null && $this->getMaximum() !== null)
            {
                return (int)(($this->getMinimum() + $this->getMaximum()) / 2);
            }
            else if ($this->getMinimum() !== null)
            {
                return $this->getMinimum();
            }
            else if ($this->getMaximum() !== null)
            {
                return $this->getMaximum();
            }
            return 3.14159265359;
            break;
        case 'string':
            switch($format)
            {
            case 'byte':
                return base64_encode('Congratulations, you decoded it!').'...';
                break;
            case 'binary':
                return '...';
                break;
            case 'date':
                return '2015-10-21';
                break;
            case 'date-time':
                return '2015-10-21T19:23:41';
                break;
            case 'password':
                return 'opensesame';
                break;
            case 'url':
                return 'http://example.com';
                break;
            default:
                return 'foo';
                break;
            }
            break;
        case 'boolean':
            return true;
            break;
        default:
            return '...';
            break;
        }
        return '...';
    }
}
