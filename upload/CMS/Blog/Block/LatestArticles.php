<?php
/**
 * Modo CMS
 */
namespace Blog\Block;

/**
 * A test block
 *
 * @category   Block
 * @package    Blog
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: LatestArticles.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 */
class LatestArticles extends \Core\Model\Block\DynamicBlock
{

    public function init()
    {

        $qb = $this->getEntityManager()
                   ->getRepository('Blog\Model\Article')
                   ->createQueryBuilder('a')
                   ->orderBy('a.id', 'DESC');

        $adapter = new \Modo\Paginator($qb);
        $page = 1;
        
        if ($this->getConfigValue('paginate')) {
            $paginator = new \Zend_Paginator($adapter);
            $page = $this->getRequest()->getParam('page', 1);
            $paginator->setCurrentPageNumber($page);
            
            $this->getView()->assign('paginator', $paginator);
        }
        $offset = ($page-1)*$this->getConfigValue('count');
        $this->getView()->assign('articles', $adapter->getItems($offset, $this->getConfigValue('count')));
        $this->getView()->assign('id', $this->getConfigValue('id'));
    }

    public function configure()
    {
        $count = new \Core\Model\Block\Config\Property\Text('count', 5, true);
        $id = new \Core\Model\Block\Config\Property('id', 0, false, true, 'Core\Model\Content');
        $paginate = new \Core\Model\Block\Config\Property('paginate', false);
        
        $this->addConfigProperties(array($count, $id, $paginate));
    }
}