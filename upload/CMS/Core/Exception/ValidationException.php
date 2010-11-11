<?php

namespace Core\Exception;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    protected $_messages = array();

    public static function invalidData($array)
    {
        $exception = new self('data is invalid');
        $exception->addMessages($array);
        return $exception;
    }

    public function getMessages()
    {
        return $this->_messages;
    }

    public function addMessages($array)
    {
        $this->_messages = \array_merge($array, $this->getMessages());
    }
}