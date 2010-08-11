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
 * @version    $Id: FailedUpload.php 297 2010-05-12 13:34:56Z mike $
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