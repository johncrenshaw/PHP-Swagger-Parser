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
    
    public function getSample($schema_resolver)
    {
        return $this->_makeSample($schema_resolver, $this->getType(), $this->getFormat(), $this->getItems());
//        if ($property->getType() == 'object' && $property instanceof \Swagger\Object\Schema && $property->getFromReference()) {
//            // Object reference
//            $property->getFromReference()->getPointer()->getSegment(1);
//        } else if ($property->getFormat()) {
//            // Well known other format
//            $property->getFormat();
//        }
//        if ($property->getItems() && $property->getItems()->getRef()) {
//            // Array of known object type
//            $property->getItems()->getRef()->getPointer()->getSegment(1);
//        } else if ($property->getItems() && $property->getItems()->getType()) {
//            // Array of other type
//            $property->getItems()->getType();
//        }
    }

    public function _makeSample($schema_resolver, $type, $format, $items)
    {
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
                // TODO: Implement full sample for additional Properties
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
            return 1234567890;
            break;
        case 'number':
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
