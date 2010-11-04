<?php

namespace Core\Model;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class Address extends \Core\Model\AbstractModel
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @Column(type="string", length="255")
     * @var string
     */
    protected $addressLine1;

    /**
     * @Column(type="string", length="255")
     * @var string
     */
    protected $addressLine2;

    /**
     * @Column(type="string", length="150")
     * @var string
     */
    protected $city;

    /**
     * @Column(type="string", length="63")
     * @var string
     */
    protected $state;

    /**
     * @Column(type="string", length="7")
     * @var string
     */
    protected $zip;

    public function getId()
    {
        return $this->id;
    }

    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }
}