<?php

namespace Core\Service;

/**
 * Service for views
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Content extends \Core\Service\AbstractService
{
    /**
     * @var \Taxonomy\Service\Term
     */
    protected $_termService;

    public function getContent($id)
    {
        return $this->_em->getReference('Core\Model\Content', $id);
    }

    protected function _setTerms(\Core\Model\Content $content, array $terms, $vocabularyName = 'contentTags')
    {
        $termService = $this->getTermService();
        $newTerms = array();
        foreach($terms as $term)
        {
            $newTerms[] = $termService->getOrCreateTerm($term, $vocabularyName);
        }
        $content->setTags($newTerms);
    }

    public function setTermService(\Taxonomy\Service\Term $termService)
    {
        $this->_termService = $termService;
    }

    public function getTermService()
    {
        return $this->_termService;
    }
}