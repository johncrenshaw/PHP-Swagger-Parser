<?php
namespace Swagger\Object;

use Swagger\Json;

trait ReferencableTrait
{
    protected $_fromReference = null;

    public function hasFromReference()
    {
        return isset($this->_fromReference);
    }

    public function getFromReference()
    {
        return $this->_fromReference;
    }
    
    public function setFromReference($ref)
    {
        $this->_fromReference = $ref;
    }
}
