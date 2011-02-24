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
 * @Table(name="activity")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 */
class AbstractActivity extends AbstractModel
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="datetime")
     */
    protected $dateCreated;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
    }

    public function getModuleName()
    {
        $class = \get_class($this);
        $array = \explode('\\', $class);
        return $array[0];
    }

    public function getPartialPath($name = 'default.phtml')
    {
        $class = \get_class($this);
        $array = \explode('\\', $class);
        $folder0 = $array[count($array) - 1];

        return 'Activity/' . \lcfirst($folder0) . '/' . $name;
    }
}