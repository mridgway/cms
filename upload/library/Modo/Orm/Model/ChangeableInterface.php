<?php
/**
 * Modo CMS
 */

namespace Modo\Orm\Model;

/**
 * Description of ChangeableInterface
 *
 * @category   Modo
 * @package    Orm
 * @subpackage Model
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: ChangeableInterface.php 70 2010-01-08 17:02:45Z court $
 */
interface ChangeableInterface
{
    /**
     * Get the unique identifier for the model
     *
     * @return integer
     */
    public function getModelId();

    /**
     * Get the type of model
     *
     * @return string
     */
    public function getModelType();

    /**
     * Get the name of the property that is changing
     *
     * @return string
     */
    public function getProperty();

    /**
     * Get the revision that this change belongs to
     *
     * @return RevisionableInterface
     */
    public function getRevision();

    /**
     * Get the value of the change
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set the unique identifier for the model
     * 
     * @param integer $id
     */
    public function setModelId($id);

    /**
     * Set the type of model
     * 
     * @param string $type
     */
    public function setModelType($type);

    /**
     * Set the name of the property that is changing
     * 
     * @param string $property 
     */
    public function setProperty($property);

    /**
     * Set the revision that this change belongs to
     * 
     * @param RevisionableInterface $revision
     */
    public function setRevision(RevisionableInterface $revision);

    /**
     * Set the value of the change
     *
     * @param mixed $value
     */
    public function setValue($value);
}