<?php
/**
 * Modo CMS
 */

namespace Core\Model\Block\Config;

/**
 * Description of ConfigValue
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Property.php 297 2010-05-12 13:34:56Z mike $
 *
 */
class Property extends \Core\Model\AbstractModel
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var bool
     */
    protected $inheritable;

    /**
     * @var string
     */
    protected $inheritableFrom;

    /**
     *
     * @param string $name
     * @param string $default
     * @param bool $required
     * @param bool $inheritable
     * @param string $inheritableFrom
     */
    public function __construct($name, $default = null, $required = false, $inheritable = false, $inheritableFrom = 'Core\Model\Block')
    {
        $this->setName($name);
        $this->setDefault($default);
        $this->setRequired($required);
        $this->setInheritable($inheritable);
        $this->setInheritableFrom($inheritableFrom);
    }

    /**
     *
     * @param string $name
     * @return Property
     */
    public function setName($name)
    {
        $validator = new \Zend_Validate_StringLength(1, 150);
        if (!$validator->isValid($name)) {
            throw new \Core\Model\Exception('Property name must be between 1 and 150 characters.');
        }
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return Property
     */
    public function setDefault($value)
    {
        $this->default = $value;
        return $this;
    }

    /**
     *
     * @param bool $required
     * @return Property
     */
    public function setRequired($required)
    {
        if (!is_bool($required)) {
            throw new \Core\Model\Exception('Property->required must be a boolean value.');
        }
        $this->required = $required;
        return $this;
    }

    /**
     *
     * @param bool $inheritable
     * @return Property
     */
    public function setInheritable($inheritable)
    {
        if (!is_bool($inheritable)) {
            throw new \Core\Model\Exception('Property->inheritable must be a boolean value.');
        }
        $this->inheritable = $inheritable;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return Property
     */
    public function setInheritableFrom($inheritableFrom)
    {
        if (!class_exists($inheritableFrom)) {
            throw new \Core\Model\Exception('Inheritable class does not exist.');
        }
        $this->inheritableFrom = $inheritableFrom;
        return $this;
    }
}