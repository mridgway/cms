<?php
/**
 * Modo CMS
 */

namespace Core\Model\Layout;

/**
 * Description of Location
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Location.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @property string $sysname
 */
class Location extends \Modo\Orm\Model\AbstractModel implements \Modo\Orm\Model\VersionableInterface
{
    /**
     * @var string
     * @Id @Column(name="sysname", type="string", length="50")
     */
    protected $sysname;

    protected $content = '';

    public function __construct($sysname)
    {
        $this->setSysname($sysname);
    }

    /**
     *
     * @param string $sysname
     * @return Location
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Modo\Model\Exception('Sysname must be between 0 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    public function addContent($content)
    {
        $this->content .= (String)$content;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getSysname();
    }
}