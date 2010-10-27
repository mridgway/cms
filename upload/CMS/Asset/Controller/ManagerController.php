<?php

namespace Asset\Controller;

/**
 * Performs actions related to the administrator asset manager
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ManagerController extends \Zend_Controller_Action
{
    protected $_em;

    public function init()
    {
        $em = $this->_em = \Zend_Registry::getInstance()->get('doctrine');
    }

    public function indexAction()
    {
        $frontend = new \Asset\Model\Frontend\Manager();
        
        // returns modal dialog frontend object
        $view = new \Core\Model\View('Asset', 'manager/manager');
        $view->urlImageForm = new \Asset\Form\ImageFromUrl();
        $view->urlFlashForm = new \Asset\Form\FlashFromUrl();

        $libraryForm = new \Asset\Form\Library();
        $assetTypes = $this->_em->getRepository('Asset\Model\Type')->findall();
        $libraryForm->setTypes($assetTypes);
        $view->libraryForm = $libraryForm;

        $frontend->html = $view->render($view->getFile());

        $html = $this->getRequest()->getParam('html');
        if (isset($html)) {
            echo $frontend->html;
        } else {
            echo $frontend->success();
        }
    }

    public function uploadAction()
    {
        
        $adapter = new \Zend_File_Transfer_Adapter_Http(array(
            'magicFile' => getenv('MAGIC')
        ));
        
        // make sure it has a proper mime type
        $mimeTypeString = $adapter->getMimeType();
        $mimeType = $this->_em->getRepository('Asset\Model\MimeType')->find($mimeTypeString);
        if (!$mimeType) {
            header("HTTP/1.1 415 Unsupported Media Type");
            $frontend = new \Asset\Model\Frontend\FailedUpload();
            die($frontend->failInvalidMimeType());
        }

        // make sure it has a valid extension for this mimetype
        $fileParts = pathinfo($adapter->getFileName());
        if (!$mimeType->isValidExtension($fileParts['extension'])) {
            header("HTTP/1.1 415 Unsupported Media Type");
            $frontend = new \Asset\Model\Frontend\FailedUpload();
            die($frontend->failInvalidExtension());
        }
        $extension = $this->_em->getReference('Asset\Model\Extension', $fileParts['extension']);

        // create model
        $fileHash = $adapter->getHash('sha1');
        $groupName = $this->getRequest()->getParam('group', 'tmp');
        $group = $this->_em->getRepository('Asset\Model\Group')->find($groupName);
        $asset = new \Asset\Model\Asset($fileHash, $fileParts['filename'], $extension, $group, $mimeType);

        // set file destination path
        $adapter->addFilter('Rename', array('target' => $this->_getFilePath($group, $fileHash, $fileParts['extension'])));

        // make sure file doesn't already exist in database
        try {
            $existingAsset = $this->_em->getRepository('Asset\Model\Asset')->getAssetByGroupNameAndHash($groupName, $fileHash);
            $frontend = new \Asset\Model\Frontend\AssetList();
            $frontend->addAsset($existingAsset);
            die($frontend->success());
        } catch (\Doctrine\ORM\NoResultException $e) {}
        
        try {
            $adapter->receive();
        } catch (\Exception $e) {
            // @todo error checking
            // this file does not exist in the database but could linger in the file system
            // (possibly from a previous asset that was deleted).  we still need to add this into
            // the database if this is the case, but we should also throw an error if the problem
            // is a permission error or something else
        }
        $this->_em->persist($asset);
        $this->_em->flush();

        $frontend = new \Asset\Model\Frontend\AssetList();
        $frontend->addAsset($asset);
        echo $frontend->success();
    }

    /**
     * Gets a path for the asset and ensures that the directories exist.
     *
     * @param Group $group
     * @param string $hash
     * @return string
     */
    protected function _getFilePath(\Asset\Model\Group $group, $hash, $ext, $size = 'original')
    {
        $assetPath = APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'assets';
        $groupPath = $assetPath . DIRECTORY_SEPARATOR . $group->sysname;
        $fileFolderPath = $groupPath . DIRECTORY_SEPARATOR . substr($hash, 0, 2);
        $filePath = $fileFolderPath . DIRECTORY_SEPARATOR . $hash;

        $paths = array ($assetPath, $groupPath, $fileFolderPath, $filePath);

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        
        return $filePath . DIRECTORY_SEPARATOR . $size . '.' . $ext;
    }

    public function listAction()
    {
        $frontend = new \Asset\Model\Frontend\AssetList();
        
        $searchTerm = $this->getRequest()->getParam('search', '');
        $typeName = $this->getRequest()->getParam('type', 'all');
        $sort = $this->getRequest()->getParam('sort', 2);
        if (!isset(\Asset\Form\Library::$sorts[$sort])) {
            throw new \Exception('Invalid sort type specified.');
        }
        $sortField = \Asset\Form\Library::$sorts[$sort]['field'];
        $sortOrder = \Asset\Form\Library::$sorts[$sort]['order'];
        $currentPage = $this->getRequest()->getParam('page', 1);
        $perPage = $this->getRequest()->getParam('perPage', 1);

        $offset = ($currentPage-1) * $perPage;

        $assetCount = $this->_em->getRepository('Asset\Model\Asset')
                                ->getLibraryAssetCount($searchTerm, $typeName);

        if ($assetCount) {
            $assets = $this->_em->getRepository('Asset\Model\Asset')
                                ->getLibraryAssetList(
                                        $searchTerm,
                                        $typeName,
                                        $sortField,
                                        $sortOrder,
                                        $offset,
                                        $perPage);
            
            foreach($assets AS $asset) {
                $frontend->addAsset($asset);
            }
            $frontend->setRowCount($assetCount);
            $frontend->setPerPage($perPage);
            $frontend->setCurrentPage($currentPage);
        }

        echo $frontend->success();
    }
}