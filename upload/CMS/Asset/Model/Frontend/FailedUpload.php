<?php

namespace Asset\Model\Frontend;

/**
 * Representation of a group of assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class FailedUpload extends \Core\Model\Frontend
{
    // ERROR CODES
    const UPLOAD_FAILED = 1;
    const FILE_EXISTS = 2;
    const INVALID_MIME_TYPE = 3;
    const INVALID_EXTENSION = 4;

    public function failUpload()
    {
        return $this->setCode(self::UPLOAD_FAILED, 'Upload Failed');
    }

    public function failFileExists()
    {
        return $this->setCode(self::FILE_EXISTS, 'File already exists');
    }

    public function failInvalidMimeType()
    {
        return $this->setCode(self::INVALID_MIME_TYPE, 'Invalid mime type');
    }

    public function failInvalidExtension()
    {
        return $this->setCode(self::INVALID_EXTENSION, 'Invalid extension');
    }
}