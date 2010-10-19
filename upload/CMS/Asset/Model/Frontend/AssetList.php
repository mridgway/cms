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