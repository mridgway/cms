<?php

namespace Core\Controller;

class InstallController extends \ZendX\Application53\Controller\Action
{
    /**
     * @var \Doctrine\ORM\Tools\SchemaTool
     */
    protected $_tool;
    protected $_classes;

    /**
     * @var \Modo\Orm\VersionedEntityManager
     */
    protected $_em;

    public function init()
    {
        $em = $this->_em = \Zend_Registry::getInstance()->get('doctrine');
        $this->_tool = new \Doctrine\ORM\Tools\SchemaTool($this->_em);
        $this->_classes = array (
            $em->getClassMetadata('Core\Model\Module'),
            $em->getClassMetadata('Core\Model\Module\Block'),
            $em->getClassMetadata('Core\Model\Module\Content'),

            $em->getClassMetadata('Core\Model\AbstractPage'),
            $em->getClassMetadata('Core\Model\Page'),
            $em->getClassMetadata('Core\Model\Template'),
            $em->getClassMetadata('Core\Model\Layout'),
            $em->getClassMetadata('Core\Model\Layout\Location'),
            $em->getClassMetadata('Core\Model\PageRoute'),
            $em->getClassMetadata('Core\Model\Route'),
            $em->getClassMetadata('Core\Model\Block'),
            $em->getClassMetadata('Core\Model\Block\Config\Value'),
            $em->getClassMetadata('Core\Model\Content'),
            $em->getClassMetadata('Core\Model\View'),

            /*
            $em->getClassMetadata('Core\Model\Revision'),
            $em->getClassMetadata('Core\Model\Revision\Change'),
            $em->getClassMetadata('Core\Model\Revision\Value'),
            $em->getClassMetadata('Core\Model\Revision\Value\Integer'),
            $em->getClassMetadata('Core\Model\Revision\Value\String'),
            $em->getClassMetadata('Core\Model\Revision\Value\Text'),
             */
            
            $em->getClassMetadata('Core\Model\Content\Placeholder'),
            $em->getClassMetadata('Core\Model\Content\Text')
        );
    }

    public function indexAction()
    {
        $this->installAction();
    }

    public function installAction ()
    {
        $this->clearCacheAction();
        
        echo '<h3>Installing Core</h3>';
        echo '<b>Creating tables...</b><br/>';
        foreach ($this->_tool->getCreateSchemaSql($this->_classes) as $sql) {
            echo $sql.';<br/>'."\n";
            ob_flush();
        }
        $this->_tool->createSchema($this->_classes);
        echo '<b>Tables created.</b><br/><br/>';

        echo '<b>Creating Base Models...</b><br/>';
        $this->_createBase();
        echo '<b>Base models created.</b><br/></br>';

        echo '<b>Registering Module...</b><br/>';
        $this->_registerModule();
        echo '<b>Module registered.</b>';

        echo '<h3>Core Module Installed</h3>';
        ob_flush();

        // Install default modules
        $this->_helper->actionStack('install', 'install', 'user');
        ob_flush();
        $this->_helper->actionStack('install', 'install', 'blog');
        ob_flush();
        $this->_helper->actionStack('install', 'install', 'asset');
    }

    public function _createBase ()
    {
        $homepage = new \Core\Model\Route('');
        $homepage->isDirect = false;
        $homepage->sysname = 'home';
        $this->_em->persist($homepage);

        $moduleDirect = new \Core\Model\Route('direct/:module/:controller/:action');
        $moduleDirect->sysname = 'DirectModule';
        $moduleDirect->isDirect = true;
        $this->_em->persist($moduleDirect);

        $coreDirect = new \Core\Model\Route('direct/:controller/:action');
        $coreDirect->sysname = 'DirectCore';
        $coreDirect->isDirect = true;
        $this->_em->persist($coreDirect);

        $moduleAjax = new \Core\Model\Route('ajax/:module/:controller/:action');
        $moduleAjax->sysname = 'AjaxModule';
        $moduleAjax->isDirect = true;
        $this->_em->persist($moduleAjax);

        $coreAjax = new \Core\Model\Route('ajax/:controller/:action');
        $coreAjax->sysname = 'AjaxCore';
        $coreAjax->isDirect = true;
        $this->_em->persist($coreAjax);

        $coreAjaxController = new \Core\Model\Route('ajax/:action');
        $coreAjaxController->sysname = 'AjaxCoreAjax';
        $coreAjaxController->controller = 'ajax';
        $coreAjaxController->isDirect = true;
        $this->_em->persist($coreAjaxController);

        echo 'Creating locations<br/>';
        $left = new \Core\Model\Layout\Location('left');
        $right = new \Core\Model\Layout\Location('right');
        $main = new \Core\Model\Layout\Location('main');

        echo 'Creating layouts<br/>';
        $layout1 = new \Core\Model\Layout('default');
        $layout1->setTitle('3 Columns');
        $layout1->setLocations(array($left, $right, $main));
        $this->_em->persist($layout1);

        $layout2 = new \Core\Model\Layout('2col');
        $layout2->setTitle('2 Columns (right sidebar)');
        $layout2->setLocations(array($right, $main));
        $this->_em->persist($layout2);

        $layout3 = new \Core\Model\Layout('2colalt');
        $layout3->setTitle('2 Columns (left sidebar)');
        $layout3->setLocations(array($left, $main));
        $this->_em->persist($layout3);

        $layout4 = new \Core\Model\Layout('1col');
        $layout4->setTitle('1 Column');
        $layout4->setLocations(array($main));
        $this->_em->persist($layout4);

        echo 'Creating a text view<br/>';
        $textView = new \Core\Model\View('Core', 'Text', 'default');
        $textView->label = 'Text Block';
        $this->_em->persist($textView);

        echo 'Creating a placeholder view<br/>';
        $placeholderView = new \Core\Model\View('Core', 'Placeholder', 'default');
        $placeholderView->label = 'Placeholder Block';
        $this->_em->persist($placeholderView);

        echo 'Creating a default form view<br/>';
        $formView = new \Core\Model\View('Core', 'Form', 'default');
        $formView->label = 'Form Block';
        $this->_em->persist($formView);
        
        $this->_em->flush();
    }

    public function _registerModule()
    {
        $moduleName = 'Core';
        $module = \Core\Module\Registry::getInstance()->getConfigModule($moduleName);

        $module->getContentType('Text')->addable = true;
        $module->getBlockType('StaticBlock')->addable = true;

        $this->_em->persist($module);
        $this->_em->flush();
    }

    public function addBlockAction ()
    {
        /* @var $page Core\Model\AbstractPage */
        $page = $this->_em->getRepository('Core\Model\AbstractPage')->find($this->getRequest()->getParam('page'));
        $location = $this->_em->getRepository('Core\Model\Layout\Location')->find($this->getRequest()->getParam('location'));
        $view = $this->_em->getRepository('Core\Model\View')->getView('Core', 'Text', 'default');

        $content = new \Core\Model\Content\Text('Test', 'Test', false);
        $block = new \Core\Model\Block\StaticBlock($content, $view);
        $page->addBlock($block, $location, 0);

        $this->_em->persist($content);
        $this->_em->persist($block);
        $this->_em->flush();
    }

    public function clearCacheAction()
    {
        $memcacheImpl = \Zend_Registry::get('doctrine')->getConfiguration()->getMetadataCacheImpl();
        if($memcacheImpl instanceof \Doctrine\Common\Cache\MemcacheCache) {
            $memcacheImpl->getMemcache()->flush();
        } else if ($memcacheImpl instanceof \Doctrine\Common\Cache\ApcCache) {
            apc_clear_cache('user');
        }
    }
}