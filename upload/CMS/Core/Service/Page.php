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
                $newValue = new \Core\Model\Block\Config\Value($value->getName(), $value->getValue(), $value->getInheritsFrom());
                $newBlock->addConfigValue($newValue);
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
        $blockService = Manager::get('Block');
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
        \xdebug_break();
        $form = new \Core\Form\Page();
        $form->populate($data);

        if ($form->isValid($data)) {

            $route = new \Core\Model\Route($data['pageRoute']);
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
}
