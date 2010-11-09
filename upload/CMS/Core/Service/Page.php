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
        if(!$id) {
            throw new \Exception('Page id not set.');
        }

        $page = $this->_em->getRepository('Core\Model\Page')->getPageForRender($id);

        if (!$page) {
            throw new \Exception('Page does not exist.');
        }

        return $page;
    }

    /**
     * Gets a page if the user has the permission specified by $actionType.
     *
     * @param integer $id
     * @param string $actionType
     * @return \Core\Model\Page
     */
    public function getPageIfAllowed($id, $actionType)
    {
        $page = $this->getPage($id);
        
        $this->isAllowed($page, $actionType);

        return $page;
    }

    /**
     * Determines if the user has permission to do $actionType for $page.
     *
     * @param \Core\Model\Page $page
     * @param string $actionType
     * @return boolean
     */
    public function isAllowed($page, $actionType)
    {
        if(!$this->getAuth()->getIdentity()->isAllowed($page, $actionType)) {
            throw new \Exception('Not allowed to ' . $actionType . ' page.');
        }

        return true;
    }

    /**
     * Ensures that a variable is a template or can be used to find a template
     *
     * @param Core\Model\Template|string|int $template
     * @return Core\Model\Template
     */
    public function ensureTemplate(&$template)
    {
        if (!($template instanceof \Core\Model\Template)) {
            if (is_int($template)) {
                $template = $this->getEntityManager()->find('Core\Model\Template', $template);
            } else {
                $template = $this->getEntityManager()->getRepository('Core\Model\Template')->findOneBySysname($template);
            }
        }

        if (!($template instanceof \Core\Model\Template)) {
            return false;
        }

        return true;
    }

    /**
     * Creates a page from a template replacing any placeholders with the appropriate objects.
     * Placeholders should be string => Core\Model\Content
     *
     * @param \Core\Model\Template
     * @return \Core\Model\Page
     */
    public function createPageFromTemplate($template, $placeholders = array())
    {
        if (!$this->ensureTemplate($template)) {
            throw new \Exception('Invalid template provided.');
        }

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
            }
            // Set block config
            foreach($block->getConfigValues() as $name => $value) {
                $newBlock->setConfigValue($value->getName(), $value->getValue(), $value->getInheritsFrom());
            }
            $page->addBlock($newBlock);
            $replacements[spl_object_hash($block)] = $newBlock;
        }

        // Fix config inheritance to point to the new blocks
        foreach ($page->getBlocks() AS $newBlock) {
            foreach ($newBlock->getConfigValues() as $name => $value) {
                if ($value->getInheritsFrom()) {
                    $value->setInheritsFrom($replacements[spl_object_hash($value->getInheritsFrom())]);
                }
            }
        }

        return $page;
    }

    /**
     * Gets all config values or content type properties for all blocks on a page.
     * 
     * @param \Core\Model\AbstractPage $page
     * @return array
     */
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
     * Applies page data edits.
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

            if($page->pageRoute->route->template != $data['pageRoute'])
            {
                $this->_em->remove($page->pageRoute);
                $this->_em->remove($page->pageRoute->route);

                $route = $this->_routeService->create($data['pageRoute']);
                $this->_em->persist($route);

                $pageRoute = $route->routeTo($page);
                $this->_em->persist($pageRoute);

                $page->pageRoute = $pageRoute;
            }

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

    /**
     * Adds a block to a page.
     * 
     * @param \Core\Model\Page $page
     * @param \Core\Model\Block $block
     * @param \Core\Model\Layout\Location $location
     */
    public function addBlock(\Core\Model\Page $page, \Core\Model\Block $block, \Core\Model\Layout\Location $location)
    {
        $page->addBlock($block, $location);
        $this->_em->persist($block);
        $this->_em->flush();
    }

    /**
     * Updates all blocks on a page with the values specified in $pageObject.  The $pageObject structure must match the \Core\Model\Page structure.
     *
     * @param \Core\Model\Page $page
     * @param \stdClass $pageObject
     */
    public function update(\Core\Model\Page $page, \stdClass $pageObject)
    {
        foreach($pageObject->layout->locations as $location)
        {
            foreach($location as $block)
            {
                $this->getBlockService()->update($page->getBlock($block->id), $block);
            }
        }
        $this->_em->flush();
    }

    public function getBlockService()
    {
        return $this->_blockService;
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
