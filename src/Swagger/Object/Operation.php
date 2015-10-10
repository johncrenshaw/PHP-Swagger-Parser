<?php
namespace Swagger\Object;

class Operation extends AbstractObject
{
    public function getTags()
    {
        return $this->getDocumentProperty('tags');
    }
    
    public function setTags($tags)
    {
        return $this->setDocumentProperty('tags', $tags);
    }
    
    public function getSummary()
    {
        return $this->getDocumentProperty('summary');
    }
    
    public function setSummary($summary)
    {
        return $this->setDocumentProperty('summary', $summary);
    }
    
    public function getDescription()
    {
        return $this->getDocumentProperty('description');
    }
    
    public function setDescription($description)
    {
        return $this->setDocumentProperty('description', $description);
    }
    
    public function getExternalDocs()
    {
        return $this->getDocumentObjectProperty('externalDocs', ExternalDocs::class);
    }
    
    public function setExternalDocs(ExternalDocs $externalDocs)
    {
        return $this->setDocumentObjectProperty('externalDocs', $externalDocs);
    }
    
    public function getOperationId()
    {
        return $this->getDocumentProperty('operationId');
    }
    
    public function setOperationId($operationId)
    {
        return $this->setDocumentProperty('operationId', $operationId);
    }
    
    public function getConsumes()
    {
        return $this->getDocumentProperty('consumes');
    }
    
    public function setConsumes($consumes)
    {
        return $this->setDocumentProperty('consumes', $consumes);
    }
    
    public function getProduces()
    {
        return $this->getDocumentProperty('produces');
    }
    
    public function setProduces($produces)
    {
        return $this->setDocumentProperty('produces', $produces);
    }
    
    public function getParameters()
    {
        return $this->getDocumentParameterProperty('parameters');
    }
    
    public function setParameters($parameters)
    {
        return $this->setDocumentObjectProperty('parameters', $parameters);
    }
    
    public function getResponses()
    {
        return $this->getDocumentObjectProperty('responses', Responses::class);
    }
    
    public function setResponses(Responses $responses)
    {
        return $this->setDocumentObjectProperty('responses', $responses);
    }
    
    public function getSchemes()
    {
        return $this->getDocumentProperty('schemes');
    }
    
    public function setSchemes($schemes)
    {
        return $this->setDocumentProperty('schemes', $schemes);
    }
    
    public function getDeprecated()
    {
        return $this->getDocumentProperty('deprecated');
    }
    
    public function setDeprecated($deprecated)
    {
        return $this->setDocumentProperty('deprecated', $deprecated);
    }
    
    public function getSecurity()
    {
        return $this->getDocumentObjectProperty('security', SecurityRequirement::class);
    }
    
    public function setSecurity($security)
    {
        return $this->setDocumentObjectProperty('security', $security);
    }

    public function getHAR($document, $method_name, $path_name)
    {
        return [
            "method"=>strtoupper($method_name),
            "url"=>'https://'.$document->getHost().$document->getBasePath().$path_name,
            "httpVersion"=>"HTTP/1.1",
            "cookies"=>$this->getHARCookies($document),
            "headers"=>$this->getHARHeaders($document),
            "queryString"=>$this->getHARQueryString($document),
            "postData"=>(object)$this->getHARPostData($document),
//            "headersSize"=>150,
//            "bodySize"=>0,
//            "comment"=>"",
        ];
    }

    public function getHARCookies($document)
    {
        return [];
    }

    public function getAllParametersByType($document, $type)
    {
        // TODO: Include parameters implied by the security scheme
        // TODO: Include global request parameters

        $ret = [];
        if ($parameters = $this->getParameters())
        {
            foreach($parameters as $parameter)
            {
                if (!$parameter)
                {
                    continue;
                }

                // Resolve references
                $parameter = $document->getSchemaResolver()->resolveReference($parameter);

                if ($parameter->getIn() == $type)
                {
                    $ret[] = $parameter;
                }
            }
        }
        
        return $ret;
    }

    public function getHARHeaders($document)
    {
        $ret = [];
        foreach ($this->getAllParametersByType($document, 'header') as $parameter)
        {
            $ret[] = [
                'name'=>$parameter->getName(),
                'value'=>$parameter->getSample(),
            ];
        }
        return $ret;
    }

    public function getHARQueryString($document)
    {
        $ret = [];
        foreach ($this->getAllParametersByType($document, 'query') as $parameter)
        {
            $ret[] = [
                'name'=>$parameter->getName(),
                'value'=>$parameter->getSample(),
            ];
        }
        return $ret;
    }

    public function getHARPostData($document)
    {
        $ret = [];
        foreach ($this->getAllParametersByType($document, 'body') as $parameter)
        {
            $ret = $parameter->getSchema()->getSample();
        }
        if (!empty($ret))
        {
            $ret = [
                'size'=>0,
                'mimeType'=>'application/json',
                'params'=>[],
                'paramsObj'=>false,
                'text'=>json_encode($ret),
                "jsonObj"=>$ret,
            ];
        }
        if (empty($ret))
        {
            // Check for the alternate formdata variation on body parameters
            foreach ($this->getAllParametersByType($document, 'formData') as $parameter)
            {
                $ret[] = [
                    'name'=>$parameter->getName(),
                    'value'=>$parameter->getSample(),
                ];
            }
            if (!empty($ret))
            {
                $ret = [
                    "mimeType"=>'application/x-www-form-urlencoded',
                    "params"=>$ret,
                ];
            }
        }
        return $ret;
    }
}
