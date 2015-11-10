<?php
namespace Swagger\Object;

class Schema extends AbstractObject implements TypeObjectInterface, ReferentialInterface
{
    use TypeObjectTrait,
        ReferentialTrait,
        ReferencableTrait,
        PropertiesTrait;
    
    public function getDescription()
    {
        return $this->getDocumentProperty('description');
    }
    
    public function setDescription($description)
    {
        return $this->setDocumentProperty('description', $description);
    }

    public function getProperties()
    {
        $ret = $this->getDocumentObjectProperty('properties', Properties::class);
        if ($ret)
        {
            $ret->setParentSchema($this);
        }
        return $ret;
    }
    
    public function getDiscriminator()
    {
        return $this->getDocumentProperty('discriminator');
    }
    
    public function setDiscriminator($discriminator)
    {
        return $this->setDocumentProperty('discriminator', $discriminator);
    }
    
    public function getReadOnly()
    {
        return $this->getDocumentProperty('readOnly');
    }
    
    public function setReadOnly($readOnly)
    {
        return $this->setDocumentProperty('readOnly', $readOnly);
    }
    
    public function getXml()
    {
        return $this->getDocumentObjectProperty('xml', Xml::class);
    }
    
    public function setXml(Xml $xml)
    {
        return $this->setDocumentObjectProperty('xml', $xml);
    }
    
    public function getExternalDocs()
    {
        return $this->getDocumentObjectProperty('externalDocs', ExternalDocs::class);
    }
    
    public function setExternalDocs(ExternalDocs $externalDocs)
    {
        return $this->setDocumentObjectProperty('externalDocs', $externalDocs);
    }
    
    public function getExample()
    {
        return $this->getDocumentProperty('example');
    }
    
    public function setExample($example)
    {
        return $this->setDocumentProperty('example', $example);
    }

    public function getRequired()
    {
        return $this->getDocumentProperty('required');
    }
    
    public function setRequired($required)
    {
        return $this->setDocumentProperty('required', $required);
    }
    
    public function getSample($schema_resolver)
    {
        $ret = [];
        foreach($this->getAllProperties($schema_resolver) as $property_name=>$property)
        {
            $property = $schema_resolver->resolveReference($property);
            if ($property->getVendorExtension('sample-visible') === false)
            {
                // Skip the item if it isn't intended to be shown in the sample
                continue;
            }
            $ret[$property_name] = $property->getSample($schema_resolver);
        }
        if ($additional_properties = $this->getAdditionalProperties()) {
            $additional_properties = $schema_resolver->resolveReference($additional_properties);
            $ret['foo'] = $additional_properties->getSample($schema_resolver);
            $ret['...'] = '...';
        }
        return $ret;
    }
}
