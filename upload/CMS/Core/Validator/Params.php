<?php

namespace Core\Validator;

/**
 * Checks database to make sure the given route does not conflict with an existing route.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Validator
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Params extends \Zend_Validate_Abstract
{
    CONST PARAM = 'path';
    CONST NOTUNIQUE = 'notUnique';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    protected $_messageTemplates = array(
        self::PARAM => "may only contain letters, numbers, dash(-), underscore(_)",
        self::NOTUNIQUE => "the parameters conflict with an existing page route"
    );

    public function isValid($value, $context = null)
    {
        $isValid = true;
        $this->_value = $value;

        if('' != \preg_replace('/[a-z0-9-_]/i', '', $value)) {
            $this->_error(self::PARAM);
            $isValid = false;
        }

        $pageRouteById = $this->getEntityManager()->getReference('Core\Model\PageRoute', $context['page_route_id']);
        unset($context['page_route_id']);

        $pageRouteByParams = $this->getEntityManager()->getRepository('Core\Model\PageRoute')->findOneBy(array('params' => \serialize($context), 'route' => $pageRouteById->getRoute()->getId()));
        if($pageRouteByParams && $pageRouteById != $pageRouteByParams) {
            $this->_error(self::NOTUNIQUE);
            $isValid = false;
        }

        return $isValid;
    }

    public function getEntityManager()
    {
        if(!$this->_em) {
            $this->setEntityManager(\Zend_Registry::get('serviceContainer')->getService('doctrine'));
        }
        return $this->_em;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }
}