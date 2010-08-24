<?php

namespace Core\Model\Block\Config;

/**
 * A persistable configuration value
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @Table(name="Block_Config_Value")
 * @property int $id
 */
class Value extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var Block
     * @ManyToOne(targetEntity="Core\Model\Block")
     * @JoinColumn(name="block_id", referencedColumnName="id", nullable="false")
     */
    protected $block;

    /**
     * @var string
     * @Column(name="name", type="string", length="150", nullable="false")
     */
    protected $name;

    /**
     * @var mixed
     * @Column(name="value", type="string", length="500", nullable="true")
     */
    protected $value;

    /**
     *
     * @ManyToOne(targetEntity="Core\Model\Block")
     * @JoinColumn(name="inherit_id", referencedColumnName="id", nullable="true")
     */
    protected $inheritsFrom;

    /**
     *
     * @param Block $block
     * @param string $name
     * @param mixed $value
     * @param string $inheritsFrom
     */
    public function __construct($name, $value = null, $inheritsFrom = null)
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setInheritsFrom($inheritsFrom);
    }

    /**
     * Gets the current value. If inheritsFrom is set, gets that value instead of its own.
     *
     * @param bool $inherit
     * @return mixed
     */
    public function getValue($inherit = true)
    {
        if ($inherit && null !== $this->inheritsFrom) {
            return $this->inheritsFrom->getConfigValue($this->name);
        }
        return $this->value;
    }

    /**
     *
     * @param Block $block
     * @return Value
     */
    public function setBlock(\Core\Model\Block $block)
    {
        $this->block = $block;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return Value
     */
    public function setName($name)
    {
        $validator = new \Zend_Validate_StringLength(0, 150);
        if (!$validator->isValid((string)$name)) {
            throw new \Core\Model\Exception('Name must be between 0 and 150 characters. ');
        }
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param string $value
     * @return Value
     */
    public function setValue($value = null)
    {
        if (null !== $value) {
            $validator = new \Zend_Validate_StringLength(0, 500);
            if (!$validator->isValid((string)$value)) {
                throw new \Core\Model\Exception('Value must be less than 500 characters.');
            }
        }
        $this->value = $value;
        return $this;
    }

    /**
     *
     * @param Block $inheritsFrom
     * @return Value
     */
    public function setInheritsFrom(\Core\Model\Block $inheritsFrom = null)
    {
        $this->inheritsFrom = $inheritsFrom;
        return $this;
    }
}