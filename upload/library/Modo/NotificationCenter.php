<?php

namespace Modo;

class NotificationCenter
{
    /**
     * Events that are being listened for
     *
     * @var string
     **/
    protected $_events = array();
    /**
     * undocumented class variable
     *
     * @var string
     **/
    protected $_name;
    
    public function __construct($name)
    {
        $this->_name = $name;
    }
    
    public function __toString()
    {
        return "NotificationCenter: " . $this->_name;
    }
    
    public function addObserver($observer, $callback, $name, $sender=null)
    {
        if (!isset($this->_events[$name])) {
            $this->_events[$name] = array();
        }
        
        $this->_events[$name][spl_object_hash($observer)] = array(
            'observer' => $observer,
            'callback' => $callback,
            'sender' => $sender
        );
    }
    
    public function postNotification($name, $message=null, $sender=null)
    {
        if (empty($this->_events[$name])) {
            return;
        }

        foreach($this->_events[$name] as $notification) {
            if (isset($notification['sender']) && $sender != $notification['sender']) {
                continue;
            }
            
            $notification['observer']->{$notification['callback']}($message);
        }
    }
}