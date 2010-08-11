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
 * @version    $Id: Asset.php 297 2010-05-12 13:34:56Z mike $
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