<?php

namespace Asset\Model\Frontend;

/**
 * Frontend for the asset manager
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Manager extends \Core\Model\Frontend
{

    /**
     *
     */
    public function success()
    {
        $template = new \Core\Model\View('asset', 'manager', 'templates/asset');
        $this->templates['asset'] = $template->getInstance()->render($template->getFile());

        return $this;
    }
}