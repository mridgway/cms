<?php

namespace Core\Exception;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    protected $_messages = array();

    public static function invalidData($className, $errors)
    {
        $exception = new self('data is invalid');
        $exception->addMessages($className, $errors);
        return $exception;
    }

    public function getMessages()
    {
        return $this->_messages;
    }

    public function addMessages($className, $errors)
    {
        $this->_messages[$className] = $errors;
    }

    public function __toString()
    {
        $output = '';
        foreach($this->getMessages() as $key => $value) {
            $output .= $key . ' failed validation with the following errors: ';
            foreach($value as $fieldName => $problems) {
                $output .= $fieldName . '->';
                foreach($problems as $error => $message) {
                    $output .= '[' . $error . '->' . $message . ']';
                }
                $output .= ' ';
            }
        }

        return $output;
    }
}