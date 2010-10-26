<?php

namespace Core\Service;
use Core\Exception\FormException;

/**
 * Service for pages
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Page extends \Core\Service\AbstractService
{
    /**
     * @var Core\Service\Block
     */
    protected $_blockService;

    /**
     * @var Core\Service\Route
     */
    protected $_routeService;

    /**
     * @var Core\Form\Page
     */
    protected $_defaultForm;

    /**
     * Gets a page.
     * 
     * @param integer $id
     * @return \Core\Model\Page
     */
    public function getPage($id)
    {
        $page = $this->_em->getRepository('Core\Model\Page')->getPageForRender($id);

        if (!$page) {
            throw new \Exception('Page does not exist.');
        }

        return $page;
    }

    /**
     * Creates a page from a template replacing any placeholders with the appropriate objects.
     * Placeholders should be string => Core\Model\Content
     *
     * @param \Core\Model\Template
     * @return \Core\Model\Page
     */
    public function createPageFromTemplate(\Core\Model\Template $template, $placeholders = array())
    {
        // keeps track of new blocks that replaced placeholders
        $replacements = array();

        $page = new \Core\Model\Page($template->getLayout());
        $page->setTemplate($template);
        foreach($template->getBlocks() as $block) {
            // @var $newBlock \Core\Model\Block
            $newBlock = null;
            if ($block instanceof \Core\Model\Block\StaticBlock) {
                $newBlock = new \Core\Model\Block\StaticBlock($block->getContent(), $block->getView(false));
            } else {
                $class = get_class($block);
                $newBlock = new $class($block->getView(false));
            }
            $newBlock->setWeight($block->getWeight());
            $newBlock->setLocation($block->getLocation());
            $newBlock->setInheritedFrom($block);
            // Replace placeholders
            if ($block instanceof \Core\Model\Block\StaticBlock
                    && $block->getContent() instanceof \Core\Model\Content\Placeholder
                    && array_key_exists($block->getContent()->getSysname(), $placeholders)) {
                $newBlock->setContent($placeholders[$block->getContent()->getSysname()]['content']);
                $newBlock->setView($placeholders[$block->getContent()->getSysname()]['view']);
                $replacements[$block->getId()] = $newBlock;
            }
            // Set block config
            foreach($block->getConfigValues() as $name => $value) {
                $newBlock->setConfigValue($value->getName(), $value->getValue(), $value->getInheritsFrom());
            }
            $page->addBlock($newBlock);
        }

        // Fix any config inheritance that may be pointing to placeholders
        foreach ($page->getBlocks() AS $newBlock) {
            foreach ($newBlock->getConfigValues() as $name => $value) {
                if ($value->getInheritsFrom()
                        && $newBlock->getConfigProperty($name)->getInheritable()
                        && $value->getInheritsFrom() instanceof \Core\Model\Block\StaticBlock
                        && $newBlock->getConfigProperty($name)->getInheritableFrom() != 'Core\Model\Block') {
                    $value->setInheritsFrom($replacements[$value->getInheritsFrom()->getId()]);
                }
            }
        }

        return $page;
    }

    public function getPageVariables(\Core\Model\AbstractPage $page)
    {
        $vars = array();
        $blockService = $this->_blockService;
        foreach ($page->getBlocks() AS $key => $block){
            $vars = array_merge($vars, $blockService->getVariables($block));
        }
        return array_unique($vars);
    }

    /**
     * Adds a page to the system.
     * 
     * @param array $data
     * @return boolean
     */
    public function addPage($data)
    {
        $form = $this->getDefaultForm()->populate($data);

        if ($form->isValid($data)) {

            $data = $form->getValues();
            $route = $this->_routeService->create($data['pageRoute']);
            $this->_em->persist($route);

            $layout = $this->_em->getRepository('Core\Model\Layout')->findOneBy(array('sysname' => $data['layout']));
            $page = new \Core\Model\Page($layout);

            unset($data['id']);
            unset($data['layout']);
            unset($data['pageRoute']);
            $page->setData($data);
            $this->_em->persist($page);

            $pageRoute = $route->routeTo($page);
            $this->_em->persist($pageRoute);

            $this->_em->flush();
        } else {
            throw FormException::invalidData($form);
        }

        return $page;
    }

    /**
     * Applys page data edits.
     *
     * @param \Core\Model\Page $page
     * @param array $data
     * @return \Core\Model\Page $page
     */
    public function editPage($page, $data)
    {
        $form = $this->getDefaultForm()->populate($data);

        // adds the current page route to the form for validation
        $data['currentRoute'] = $page->getPageRoute()->getRoute()->getTemplate();

        if ($form->isValid($data)) {
            $data = $form->getValues();

            $page->setLayout($this->_em->getReference('Core\Model\Layout', $data['layout']));

            $this->_em->remove($page->pageRoute);
            $this->_em->remove($page->pageRoute->route);

            $route = $this->_routeService->create($data['pageRoute']);
            $this->_em->persist($route);

            $pageRoute = $route->routeTo($page);
            $this->_em->persist($pageRoute);

            $page->pageRoute = $pageRoute;

            unset($data['id']);
            unset($data['layout']);
            unset($data['pageRoute']);
            $page->setData($data);

            $this->_em->flush();
        } else {
            throw FormException::invalidData($form);
        }

        return $page;
    }

    /**
     * Deletes a page.
     *
     * @param \Core\Model\Page $page
     */
    public function deletePage($page)
    {
        $route = $page->getPageRoute()->getRoute();

        if(!$route->getSysname())
        {
            $this->_em->remove($route);
        }

        foreach($page->dependentContent as $content)
        {
            $staticBlocks = $this->_em->getRepository('Core\Model\Block\StaticBlock')->getContentStaticBlocks($content);
            foreach($staticBlocks as $block)
            {
                $this->_em->remove($block);
            }
            $this->_em->remove($content);
        }

        $this->_em->remove($page);
        $this->_em->flush();
    }

    public function setBlockService($blockService)
    {
        $this->_blockService = $blockService;
    }

    public function setRouteService($routeService)
    {
        $this->_routeService = $routeService;
    }

    public function setDefaultForm($form)
    {
        if(\is_string($form))
        {
            $this->_defaultForm = new $form();
        }
        else
        {
            $this->_defaultForm = $form;
        }
    }

    public function getDefaultForm()
    {
        if(null == $this->_defaultForm)
        {
            throw new \Exception(get_class($this) . ' does not have a default form set.  Make sure to inject a default form after initialization.');
        }

        return $this->_defaultForm;
    }
}
