<?php

namespace Asset\Model\Frontend;

/**
 * Frontend for an asset
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Asset extends \stdClass
{
    public function __construct(\Asset\Model\Asset $asset)
    {
        $this->id = $asset->getId();
        $this->thumb = $asset->getUrl('small');
        $this->url_template = $asset->getUrl('{size}');
        $this->name = $asset->getName();
        $this->caption = $asset->getCaption();
        $this->type = $asset->getMimeType()->getType()->getSysname();
        $this->upload_date = $asset->getUploadDate()->format('F j, Y, g:i a');
        $this->sizes = array();
        if ($asset->getMimeType()->getType()->getSysname() == 'image') {
            foreach($asset->getGroup()->getSizes() AS $size)
            {
                $this->sizes[] = array(
                    'sysname' => $size->sysname,
                    'title' => $size->title,
                    'width' => $size->width,
                    'height' => $size->height,
                    'cropped' => $size->crop
                );
            }
        }
        $this->actions = array('Insert', 'Edit', 'Delete');
    }
}