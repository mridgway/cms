<?php

namespace Core\Model\Block;

/**
 * Interface for creating form fields from configuration properties
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
interface ConfigurableInterface
{
    public function getConfigurationField();
}