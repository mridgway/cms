<?php
/**
 * Modo CMS
 */

namespace Asset\Model\Frontend;

/**
 * Returns information for asset manager
 *
 * @category   Model
 * @package    Asset
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Manager.php 297 2010-05-12 13:34:56Z mike $
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