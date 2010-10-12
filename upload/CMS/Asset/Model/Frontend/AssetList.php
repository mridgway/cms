<?php

namespace Asset\Model\Frontend;

/**
 * Frontend for a list of assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class AssetList extends \Core\Model\Frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->data = new \stdClass();
        $this->data->rowCount = 0;
        $this->data->perPage = 0;
        $this->data->currentPage = 1;
        $this->data->assets = array();
        $template = new \Core\Model\View('Asset', 'manager/templates/asset');
        $this->templates['asset'] = $template->render($template->getFile());
        $template = new \Core\Model\View('Asset', 'manager/templates/insert');
        $this->templates['Insert'] = $template->render($template->getFile());
        $editForm = new \Asset\Form\Asset();
        $editForm->getElement('id')->setValue('{id}');
        $editForm->getElement('name')->setValue('{name}');
        $editForm->getElement('caption')->setValue('{caption}');
        $this->templates['Edit'] = $editForm->render();
        $template = new \Core\Model\View('Asset', 'manager/templates/delete');
        $this->templates['Delete'] = $template->render($template->getFile());
    }

    public function success()
    {
        return $this;
    }

    public function addAsset(\Asset\Model\Asset $asset)
    {
        $frontendAsset = new Asset($asset);
        $this->data->assets[] = $frontendAsset;
    }

    public function setRowCount($count)
    {
        $this->data->rowCount = $count;
    }

    public function setPerPage($num)
    {
        $this->data->perPage = $num;
    }

    public function setCurrentPage($page)
    {
        $this->data->currentPage = $page;
    }
}