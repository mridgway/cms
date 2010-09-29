<?php

namespace Asset\Controller;

/**
 * Installs the asset module
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class InstallController extends \Core\Controller\AbstractInstallController
{

    protected $moduleName = 'Asset';

    protected $classes = array(
        'Asset\Model\Asset',
        'Asset\Model\Group',
        'Asset\Model\MimeType',
        'Asset\Model\Size',
        'Asset\Model\Type',
        'Asset\Model\Extension'
    );

    public function installAction()
    {
        echo '<h3>Installing Asset Module</h3>';
        echo '<b>Creating tables...</b><br/>';
        ob_flush();
        $this->createSchema();
        echo '<b>Tables created.</b><br/><br/>';

        echo '<b>Registering module...</b><br/>';
        ob_flush();
        $this->registerModule();
        echo '<b>Module registered.</b><br/><br/>';

        echo '<b>Installing base...</b><br/>';
        ob_flush();
        $this->_loadBase();
        echo '<b>Base installed.</b><br/>';

        echo '<h3>Asset Module Installed</h3>';
        ob_flush();
    }

    public function _loadBase()
    {
        // asset route
        $defaults = array(
            'module' => 'asset',
            'controller' => 'asset',
            'action' => 'view'
        );
        $route = new \Core\Model\Route('assets/:group/:first_two/:hash/:file_name', 'asset', $defaults);
        $route->setIsDirect(true);
        $this->_em->persist($route);

        // asset types
        $types = array(
            'image' => new \Asset\Model\Type('image', 'Image'),
            'document' => new \Asset\Model\Type('document', 'Document'),
            'audio' => new \Asset\Model\Type('audio', 'Audio'),
            'flash' => new \Asset\Model\Type('flash', 'Flash'),
        );
        foreach ($types AS $type) {
            $this->_em->persist($type);
        }

        // allowed mimetypes and their extensions
        $mimeTypes = array(
            'jpeg' => array(
                'mimeType' => new \Asset\Model\MimeType('image/jpeg', $types['image']),
                'extensions' => array(
                    new \Asset\Model\Extension('jpeg'),
                    new \Asset\Model\Extension('jpg')
                )
            ),
            'gif' => array(
                'mimeType' => new \Asset\Model\MimeType('image/gif', $types['image']),
                'extensions' => array(
                    new \Asset\Model\Extension('gif')
                )
            ),
            'png' => array(
                'mimeType' => new \Asset\Model\MimeType('image/png', $types['image']),
                'extensions' => array(
                    new \Asset\Model\Extension('png')
                )
            ),
            'pdf' => array(
                'mimeType' => new \Asset\Model\MimeType('application/pdf', $types['document']),
                'extensions' => array(
                    new \Asset\Model\Extension('pdf')
                )
            ),
            'rtf' => array(
                'mimeType' => new \Asset\Model\MimeType('application/rtf', $types['document']),
                'extensions' => array(
                    new \Asset\Model\Extension('rtf')
                )
            ),
            'plain' => array(
                'mimeType' => new \Asset\Model\MimeType('text/plain', $types['document']),
                'extensions' => array(
                    new \Asset\Model\Extension('txt')
                )
            ),
            'excel' => array(
                'mimeType' => new \Asset\Model\MimeType('application/vnd.ms-excel', $types['document']),
                'extensions' => array(
                    new \Asset\Model\Extension('xla'),
                    new \Asset\Model\Extension('xlc'),
                    new \Asset\Model\Extension('xlm'),
                    new \Asset\Model\Extension('xls'),
                    new \Asset\Model\Extension('xlt'),
                    new \Asset\Model\Extension('xlw')
                )
            ),
            'word' => array(
                'mimeType' => new \Asset\Model\MimeType('application/msword', $types['document']),
                'extensions' => array(
                    new \Asset\Model\Extension('doc')
                )
            ),
            'openxmloffice' => array(
                'mimeType' => new \Asset\Model\MimeType('application/vnd.openxmlformats', $types['document']),
                'extensions' => array(
                    new \Asset\Model\Extension('docx'),
                    new \Asset\Model\Extension('pptx'),
                    new \Asset\Model\Extension('xlsx')
                )
            ),
            'mpeg-audio' => array(
                'mimeType' => new \Asset\Model\MimeType('audio/mpeg', $types['audio']),
                'extensions' => array(
                    new \Asset\Model\Extension('mp3')
                )
            ),
            'flash' => array(
                'mimeType' => new \Asset\Model\MimeType('application/x-shockwave-flash', $types['flash']),
                'extensions' => array(
                    new \Asset\Model\Extension('swf')
                )
            )

        );
        foreach ($mimeTypes AS $type) {
            $type['mimeType']->addExtensions($type['extensions']);
            $this->_em->persist($type['mimeType']);
        }

        // groups
        $groups = array(
            'default' => new \Asset\Model\Group('default', 'Default')
        );
        // group sizes (only for images)
        $groups['default']->addSize('small', new \Asset\Model\Size(100, 100))
                          ->addSize('medium', new \Asset\Model\Size(250, 250))
                          ->addSize('large', new \Asset\Model\Size(500, 500))
                          ->addSize('small-cropped', new \Asset\Model\Size(100, 100, true))
                          ->addSize('medium-cropped', new \Asset\Model\Size(250, 250, true))
                          ->addSize('large-cropped', new \Asset\Model\Size(500, 500, true));
        foreach ($groups AS $group) {
            $this->_em->persist($group);
        }

        $this->_em->flush();
    }
}