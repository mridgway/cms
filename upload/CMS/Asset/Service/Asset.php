<?php

namespace Asset\Service;

/**
 * Service for assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Asset
{
    /**
     * @var Asset\Service\Extension
     */
    protected $_extensionService;

    /**
     * @var Asset\Service\Group
     */
    protected $_groupService;

    /**
     * @var Asset\Service\MimeType
     */
    protected $_mimeTypeService;
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em = null;

    /**
     * @todo Use mediator instead of this in controller
     *
     * @param array $data
     * @return Asset\Form\Asset
     */
    public function getAddForm($data = null)
    {
        return new \Asset\Form\Asset();
    }

    /**
     * @todo Use mediator instead of this in controller
     *
     * @param Asset\Model\Asset $asset
     * @param array $data
     * @return Asset\Form\Asset
     */
    public function getEditForm(\Asset\Model\Asset $asset, $data = null)
    {
        $form = new \Asset\Form\Asset;
        $form->setObject($asset);
        if (null !== $data) {
            $form->populate($data);
        }
        return $form;
    }

    /**
     * Moves an asset to a different group.
     *
     * @param string $url
     * @param string $groupName
     * @return \Asset\Model\Asset
     */
    public function getWithUrlAndMove($url, $newGroupName)
    {
        $asset = null;

        if ($url != '') {
            $em = $this->getEntityManager();
            $parts = explode('?', $url);
            $parts = explode('/', $parts[0]);
            $groupName = $parts[2];
            $hash = $parts[4];
            $asset = $em->getRepository('Asset\Model\Asset')
                    ->getAssetByGroupNameAndHash($groupName, $hash);
            $this->changeGroup($asset, $newGroupName);
        }

        return $asset;
    }

    /**
     * Moves an image from one group to another. Old files are left in place,
     * but will not be linked to from the database.
     *
     * @param \Asset\Model\Asset $asset
     * @param \Asset\Model\Group $group
     */
    public function changeGroup(&$asset, $group)
    {
        if (!$asset instanceof \Asset\Model\Asset) {
            $asset = $this->getEntityManager()
                    ->getRepository('Asset\Model\Asset')
                    ->find($asset);
        }

        if (!$group instanceof \Asset\Model\Group) {
            $groupRepo = $this->getEntityManager()->getRepository('Asset\Model\Group');
            if (is_int($group)) {
                $group = $groupRepo->find($group);
            } else {
                $group = $groupRepo->findOneBySysname($group);
            }
            if (!$group instanceof \Asset\Model\Group) {
                throw new \Exception('Asset group is invalid.');
            }
        }

        if ($asset->getGroup() != $group) {
            try {
                // Check for existing image in that group, if exists return that
                $existingAsset = $this->getEntityManager()->getRepository('Asset\Model\Asset')
                    ->getAssetByGroupNameAndHash($group->getSysname(), $asset->getSysname());
                $this->getEntityManager()->remove($asset);
                $asset = $existingAsset;
            } catch (\Doctrine\ORM\NoResultException $e) {
                if ('tmp' != $asset->getGroup()->getSysname()) {
                    // Copy instead of move
                    $newAsset = new \Asset\Model\Asset($asset->getSysname(), $asset->getName(), $asset->getExtension(), $group, $asset->getMimeType());
                    $newAsset->setCaption($asset->getCaption());
                    $this->getEntityManager()->persist($newAsset);
                    $oldFile = $asset->getLocation();
                    $asset = $newAsset;
                } else {
                    $oldFile = $asset->getLocation();
                    $asset->setGroup($group);
                }
                if (!file_exists($asset->getLocation())) {
                    if (!file_exists($asset->getFolder())) {
                        \mkdir($asset->getFolder(), 0777, true);
                    }
                    if (!\copy($oldFile, $asset->getLocation())) {
                        throw \Exception('Copy failed');
                    }
                }
            }

            $this->getEntityManager()->flush();
        }

        return $asset;
    }

    /**
     * Creates a new Asset.
     *
     * @param string $sysname
     * @param string $name
     * @param string $extension
     * @param string $group
     * @param string $mimeType
     * @return \Asset\Model\Asset
     */
    public function create($sysname, $name, $extension, $group, $mimeType)
    {
        $extension = $this->getExtensionService()->getExtension($extension);
        $group = $this->getGroupService()->getGroup($group);
        $mimeType = $this->getMimeTypeService()->getMimeType($mimeType);

        return new \Asset\Model\Asset($sysname, $name, $extension, $group, $mimeType);
    }

    public function retrieve($id)
    {
        return $this->getEntityManager()->find('Asset\Model\Asset', $id);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function setExtensionService($extensionService)
    {
        $this->_extensionService = $extensionService;
    }

    public function getExtensionService()
    {
        return $this->_extensionService;
    }

    public function setGroupService($groupService)
    {
        $this->_groupService = $groupService;
    }

    public function getGroupService()
    {
        return $this->_groupService;
    }

    public function setMimeTypeService($mimeTypeService)
    {
        $this->_mimeTypeService = $mimeTypeService;
    }

    public function getMimeTypeService()
    {
        return $this->_mimeTypeService;
    }
}