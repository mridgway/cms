<?php

namespace Taxonomy\Controller;

/**
 * Installs the core module and all default modules
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class InstallController extends \Core\Controller\AbstractInstallController
{
    protected $moduleName = 'Taxonomy';

    protected $classes = array(
        'Taxonomy\Model\Vocabulary',
        'Taxonomy\Model\Term'
    );

    public function installAction ()
    {        
        echo '<h3>Installing Taxonomy</h3>';

        echo '<b>Registering Module...</b><br/>';
        ob_flush();
        $this->registerModule();
        echo '<b>Module registered.</b><br/><br>';

        echo '<b>Creating Base Models...</b><br/>';
        ob_flush();
        $this->_createBase();
        echo '<b>Base models created.</b><br/></br>';

        echo '<h3>Taxonomy Module Installed</h3>';
        ob_flush();
    }

    public function _createBase()
    {
        
    }
}