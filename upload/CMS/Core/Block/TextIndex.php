<?php

namespace Core\Block;

/**
 * Block for listing shared text
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Block
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class TextIndex extends \Core\Model\Block\DynamicBlock
{
    public function init()
    {
        $user = $this->getServiceContainer()->getService('auth')->getIdentity();
        if (!in_array($user->getGroup()->getSysname(), array('admin', 'root'))) {
            throw \Core\Exception\PermissionException::denied();
        }
        
        $qb = $this->getEntityManager()->getRepository('Core\Model\Content\Text')->createQueryBuilder('t');
        $qb->where('t.shared = :isShared');
        $qb->setParameter('isShared', true);

        $results = $qb->getQuery()->getResult();

        $this->getView()->assign('results', $results);
    }

    public function configure()
    {
        
    }
}