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
class Page extends \Core\Service\AbstractModel
{
    /**
     * @var Core\Service\Block
     */
    protected $_blockService;

    /**
     * @var Core\Service\Route
     */
    protected $_routeService;

    public function retrieve($id)
    {
        return $this->_retrieve($id);
    }

    // NOTE!  This is not implemented with the same interface as expected.  Layout, PageRoute, and Route models need refactored to have toArray() functions first.
    public function retrieveAsArray($id)
    {
        $page = $this->retrieve($id);

        $array =  $page->toArray();
        $array['layout']['sysname'] = $page->getLayout()->getSysname();
        $array['pageRoute']['route']['template'] = $page->getPageRoute()->getRoute()->getTemplate();
        $array['pageRoute']['route']['id'] = $page->getPageRoute()->getRoute()->getId();
        $array['pageRoute']['params']['page_route_id'] = $page->getPageRoute()->getId();

        foreach($page->getPageRoute()->getParams() as $key => $value) {
            $array['pageRoute']['params'][$key] = $value;
        }

        return $array;
    }

    public function getForm($id = null)
    {
        if($id) {
            $page = $this->retrieve($id);
            if($page->getPageRoute()->getRoute()->getSysname()) {
                $form = new \Core\Form\PageWithParams();
                $form->addRouteSubForm($page->getPageRoute());
                return $form;
            }
        }

        return new \Core\Form\PageWithTemplate();
    }

    public function create($data)
    {
        $this->getEntityManager()->beginTransaction();

        try {
            $model = $this->_create($data);
            $this->getEntityManager()->persist($model);

            $this->setObjects($model, $data);

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            throw $e;
        }

        return $model;
    }

    public function setObjects($model, $data)
    {
        if(isset($data['pageRoute']['route']['template']) && '' != $data['pageRoute']['route']['template']) {
            if($model->getPageRoute()) {
                if($data['pageRoute']['route']['template'] != $model->getPageRoute()->getRoute()->getTemplate()) {
                    $this->removeRoute($model);
                    $this->createRoute($model, $data['pageRoute']['route']['template']);
                }
            } else {
                $this->createRoute($model, $data['pageRoute']['route']['template']);
            }
        } elseif (!isset($data['pageRotue']['route']['template']) && !$model->getPageRoute()) {
            throw \Core\Exception\ValidationException::invalidData('Core\Model\Route', array('pageRoute[route][template]' => array('required' => 'a template is required.')));
        }

        if(isset($data['pageRoute']['params'])) {
            unset($data['pageRoute']['params']['page_route_id']);
            $pageRouteByParams = $this->_em->getRepository('Core\Model\PageRoute')->findOneBy(array('params' => \serialize($data['pageRoute']['params']), 'route' => $model->getPageRoute()->getRoute()->getId()));
            if(!$pageRouteByParams || ($pageRouteByParams && $pageRouteByParams->getId() == $model->getPageRoute()->getId())) {
                $model->getPageRoute()->setParams($data['pageRoute']['params']);
            } else {
                throw \Core\Exception\ValidationException::invalidData('Core\Model\PageRoute', array('pageRoute[route][params]' => array('notUnique' => 'the parameters conflict with an existing page route.')));
            }
        }

        if(isset($data['layout']['sysname']) && '' != $data['layout']['sysname']) {
            $layout = $this->_em->getRepository('Core\Model\Layout')->findOneBySysname($data['layout']['sysname']);
            $model->setLayout($layout);
        } else {
            throw \Core\Exception\ValidationException::invalidData('Core\Model\Layout', array('layout[sysname]' => array('required' => 'a layout sysname is required.')));
        }
    }

    private function removeRoute($model)
    {
        $this->_em->remove($model->getPageRoute());
        $this->_em->remove($model->getPageRoute()->getRoute());
    }

    private function createRoute($model, $template)
    {
        $route = $this->getRouteService()->create($template);
        $this->_em->persist($route);
        $pageRoute = new \Core\Model\PageRoute($route, $model);
        $this->_em->persist($pageRoute);
        $model->setPageRoute($pageRoute);
    }

    public function updatePage($data)
    {
        $this->getEntityManager()->beginTransaction();

        try {

            $model = $this->retrieve($data['id']);

            $model = $this->_update($data);

            $this->setObjects($model, $data);

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            $this->getEntityManager()->close();
            throw $e;
        }

        return $model;
    }

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
            throw new \Core\Exception\PermissionException('Not allowed to ' . $actionType . ' page.');
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
                $template = $this->getEntityManager()
                        ->find('Core\Model\Template', $template);
            } else {
                $template = $this->getEntityManager()
                        ->getRepository('Core\Model\Template')
                        ->findOneBySysname($template);
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
                $class = \get_class($block);
                $newBlock = new $class($block->getContent(), $block->getView(false));
            } else {
                $class = \get_class($block);
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
     * Sets or updates a page title.
     *
     * @param \Core\Model\Page $page
     * @param string $newTitle
     * @param string $oldTitle
     * @return \Core\Model\Page
     */
    public function updatePageTitle($page, $newTitle, $oldTitle = '')
    {
        if('' == $page->getTitle() || $oldTitle == $page->getTitle()) {
            $page->setTitle($newTitle);
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
        if (!$page->canDelete($this->getAuth()->getIdentity())) {
            throw \Core\Exception\PermissionException::denied();
        }

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

    public function getRouteService()
    {
        return $this->_routeService;
    }
}
