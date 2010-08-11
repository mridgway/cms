<?php

namespace Asset\Controller;

class AssetController extends \Zend_Controller_Action
{
    /**
     * @var \Modo\Orm\VersionedEntityManager
     */
    protected $_em;

    protected $_asset;

    public function init()
    {
        $em = $this->_em = \Zend_Registry::getInstance()->get('doctrine');

        $id = $this->getRequest()->getParam('id', null);
        $group = $this->getRequest()->getParam('group', null);
        $sysname = $this->getRequest()->getParam('hash', null);
        
        if (null !== $id) {
            $this->_asset = $this->_em->getRepository('Asset\Model\Asset')->find($id);
        } else if (null !== $group && null !== $sysname) {
            $this->_asset = $this->_em->getRepository('Asset\Model\Asset')
                                     ->getAssetByGroupNameAndHash($group, $sysname);
        }
        
        if (!$this->_asset) {
            throw new \Exception('Asset not found.'); // 404
        }
    }

    /**
     * Displays an asset. Images are the only thing that can be displayed without an actual file
     * being present. This function will create the image so that subsequent requests will be able
     * to access it directly.
     */
    public function viewAction()
    {
        $fileName = $this->getRequest()->getParam('file_name', 'original.png');
        $fileNameParts = explode('.', $fileName);
        $sizeName = $fileNameParts[0];
        
        if($fileNameParts[1] != $this->_asset->getExtension()->getSysname()) {
            throw new \Exception('Unknown extension.'); // 404
        }

        if ($this->_asset->getMimeType()->getType()->getSysname() != 'image') {
            throw new \Exception('Only images can be displayed dynamically.'); // 404
        }

        // get size info
        $size = null;
        if ($sizeName != 'original') {
            foreach ($this->_asset->getGroup()->getSizes() AS $groupSize) {
                if ($groupSize->getSysname() == $sizeName) {
                    $size = $groupSize;
                }
            }
            if (null == $size) {
                throw new \Exception('Unknown size specified.'); // 404
            }
        }

        if (!file_exists($this->_asset->getLocation($sizeName))) {
            $image = new \Imagick($this->_asset->getLocation());
            $height = $size->getHeight();
            $width = $size->getWidth();
            if ($size->getCrop()) {
                $image->cropThumbnailImage($width, $height);
                //$image = $this->_cropImage($image, $size->height, $size->width);
            } else {
                // resize image to correct dimensions
                $image->resizeImage($width, $height, \Imagick::FILTER_CATROM, 1.0, true);
            }
            // save file so that it can be accessed directly on future requests
            $image->writeImage($this->_asset->getLocation($sizeName));
        } else {
            // the image already exists, so just load it and display it
            // clearly there is a rewrite error since the request shouldn't get to this action
            // but we'll handle it gracefully
            // @todo log that the htaccess is misconfigured for direct asset access
            $image = new \Imagick($this->_asset->getLocation($sizeName));
        }
        header('Content-type: ' . $this->_asset->getMimeType()->getSysname());
        die($image);
    }

    /**
     * This is replaced by cropThumbnailImage in the Imagick library.
     * This could end up being used in order to change the behaviour of images that are smaller
     * than the crop area.
     *
     * @param Imagick $image
     * @param int $minHeight
     * @param int $minWidth
     * @return Imagick
     */
    protected function _cropImage(Imagick $image, $minHeight, $minWidth)
    {
        $width = $image->getImageWidth();
        $height = $image->getImageHeight();

        $vertical = ($height > $width) ? true : false;
        $resizeScale = ($vertical) ? $minWidth / $width : $minHeight / $height;

        // disables scaling up
        $resizeScale = ($resizeScale > 1) ? 1 : $resizeScale;

        $newWidth = $width * $resizeScale;
        $newHeight = $height * $resizeScale;
        $image->resizeImage($newWidth, $newHeight, \Imagick::FILTER_CATROM, 1.0);

        $cropX = ($newWidth - $minWidth) / 2;
        $cropY = ($newHeight - $minHeight) / 2;
        $image->cropImage($minWidth, $minHeight, $cropX, $cropY);

        return $image;
    }

    public function editAction()
    {
        $frontend = new \Asset\Model\Frontend\AssetList();

        $data = $this->getRequest()->getPost();

        /* @var $form \Asset\Form\Asset */
        $form = Core\Service\Manager::get('Asset', 'Asset')->getEditForm($this->_asset, $data);

        $frontend->html = $form->render();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($data)) {
                $form->removeElement('id');
                $this->_asset->setData($form->getValues());
                $this->_em->flush();
            } else {
                $frontend->setCode(1, $form->getErrorMessages());
            }
        }
        $frontend->addAsset($this->_asset);
        echo $frontend->success();
    }

    public function deleteAction()
    {
        $frontend = new \Core\Model\Frontend\Simple();

        $this->_em->remove($this->_asset);
        $this->_em->flush();

        echo $frontend->success();
    }
}