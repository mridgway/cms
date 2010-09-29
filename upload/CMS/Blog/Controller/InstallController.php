<?php

namespace Blog\Controller;

/**
 * Controller for installing the blog module
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class InstallController extends \Core\Controller\AbstractInstallController
{

    protected $moduleName = 'Blog';

    protected $classes = array(
        'Blog\Model\Article'
    );

    protected $text;

    public function installAction()
    {
        echo '<h3>Installing Blog Module</h3>';
        echo '<b>Creating tables...</b><br/>';
        ob_flush();
        $this->createSchema();
        echo '<b>Tables created.</b><br/><br>';

        echo '<b>Registering Module...</b><br/>';
        ob_flush();
        $this->registerModule();
        echo '<b>Module registered.</b><br/><br>';

        echo 'Adding blog article template...<br/>';
        ob_flush();
        $this->_createTemplate();

        echo 'Adding article add page...<br/>';
        ob_flush();
        $this->_createAddPage();

        echo 'Creating blog article route...<br/>';
        ob_flush();
        $this->_createRoute();

        echo 'Creating homepage...<br/><br/>';
        ob_flush();
        $this->_createHomepage();
        $this->_em->flush();
        
        echo '<h3>Blog Module Installed</h3>';
        ob_flush();
    }

    /**
     * Create the template for blog article pages to be created from
     */
    public function _createTemplate()
    {
        // the template
        $template = new \Core\Model\Template('blogArticle', $this->_em->getReference('Core\Model\Layout', 'default'));

        // create placeholder for the article
        $placeholder = new \Core\Model\Content\Placeholder('blogArticle', 'Blog\Model\Article', 'Holds the article.');
        $this->_em->persist($placeholder);

        // create the view for the article
        $view = $this->module->getBlockType('LatestArticles')->getView('default');

        // create the view for the latest article aggregator
        $latestView = $this->module->getBlockType('LatestArticles')->getView('latest');

        // create the blocks
        $placeholderView = $this->getModule('Core')->getContentType('Placeholder')->getView('default');
        $mainBlock0 = new \Core\Model\Block\StaticBlock($placeholder, $placeholderView);

        $rightBlock0 = new \Blog\Block\LatestArticles($latestView);
        $rightBlock0->setConfigValue('count', 5);
        $rightBlock0->setConfigValue('id', 0, $mainBlock0);
        $rightBlock0->setConfigValue('paginate', 0);
        
        $text = new \Core\Model\Content\Text('Add New Article', '<a href="/blog/add">Add New Article</a>');
        $this->text = $text;
        $this->_em->persist($text);
        $textView = $this->getModule('Core')->getContentType('Text')->getView('default');
        $leftBlock0 = new \Core\Model\Block\StaticBlock($text, $textView);

        // add the blocks to the template
        $template->addBlock($mainBlock0, $this->_em->getReference('Core\Model\Layout\Location', 'main'), 0);
        $template->addBlock($rightBlock0, $this->_em->getReference('Core\Model\Layout\Location', 'right'), 0);
        $template->addBlock($leftBlock0, $this->_em->getReference('Core\Model\Layout\Location', 'left'), 0);
        $this->_em->persist($template);

        return $template;
    }

    /**
     * Creates a page for adding articles to the database
     */
    public function _createAddPage()
    {
        // the page
        $page = new \Core\Model\Page($this->_em->getReference('Core\Model\Layout', '2colalt'));

        // the form block
        $formView = $this->getModule('Core')->getBlockType('Form')->getView('default');
        $block = new \Blog\Block\Form\Article($formView);

        // some text
        $text = new \Core\Model\Content\Text('Add Article', 'Here you can add an article to the homepage. By default the home page displays the last 10 blog articles.');
        $this->_em->persist($text);

        $textView = $this->getModule('Core')->getContentType('Text')->getView('default');
        $leftBlock0 = new \Core\Model\Block\StaticBlock($text, $textView);

        // the route to get to the page
        $route = new \Core\Model\Route('blog/add');
        $this->_em->persist($route->routeTo($page));
        $this->_em->persist($route);

        // add the block to the page
        $page->addBlock($block, $this->_em->getReference('Core\Model\Layout\Location', 'main'), 0);
        $page->addBlock($leftBlock0, $this->_em->getReference('Core\Model\Layout\Location', 'left'), 0);
        $this->_em->persist($page);
    }

    /**
     * Creates the route for blog article pages
     */
    public function _createRoute()
    {
        $route = new \Core\Model\Route('blog/article/:id', 'blogArticle');
        $this->_em->persist($route);
    }

    public function _createHomepage()
    {
        // the page
        $page = new \Core\Model\Page($this->_em->getReference('Core\Model\Layout', '2col'));

        // add new article block
        $textView = $this->getModule('Core')->getContentType('Text')->getView('default');
        $leftBlock0 = new \Core\Model\Block\StaticBlock($this->text, $textView);

        // main block
        $aggregatorView = $this->getModule()->getBlockType('LatestArticles')->getView('default');
        $this->_em->persist($aggregatorView);
        $rightBlock0 = new \Blog\Block\LatestArticles($aggregatorView);
        $rightBlock0->setConfigValue('count', 10);
        $rightBlock0->setConfigValue('paginate', true);

        $page->addBlock($leftBlock0, $this->_em->getReference('Core\Model\Layout\Location', 'right'), 1);
        $page->addBlock($rightBlock0, $this->_em->getReference('Core\Model\Layout\Location', 'main'), 0);

        $homeRoute = $this->_em->getRepository('Core\Model\Route')->getRoute('home');
        $this->_em->persist($homeRoute->routeTo($page));

        $this->_em->persist($page);
    }
}