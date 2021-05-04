<?php

#doc
#	classname:	Abstracto
#	scope:		abstract
#
#/class

/**
 * Class Abstracto
 */
abstract class Abstracto
{
    #	internal variables
    /**
     * @var
     */
    public $m_properties;

    /**
     * @var
     */
    private $m_consulta;

    /**
     * Constructor
     */
    function I_Init()
    {
        $this->m_consulta = new CONSULTAS;
        $reflectionClass = new ReflectionClass($this);
        $propertyArray = $reflectionClass->getProperties();
        foreach ($propertyArray as $property) {
            $this->m_properties[] = $property->getName();
        }
    }

    /**
     * @param $methodName
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $methodType = substr($methodName, 0, 3);
        $propertyName = substr($methodName, 3);

        $propertyName = (!property_exists(get_class($this), $propertyName)) ? lcfirst($propertyName) : $propertyName;
        if ($methodType === 'get') {
            if (empty($arguments)) {
                return $this->{$propertyName};
            } else {
                return $this->{$propertyName}[$arguments[0]];
            }
        }
        $this->$propertyName = $arguments[0];
    }

    /**
     * @return mixed
     */
    public function Consultas()
    {
        return $this->m_consulta;
    }
}

?>