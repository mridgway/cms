<?php

namespace Modo;

class NotificationManager
{
    /**
     * undocumented class variable
     *
     * @var string
     **/
    protected static $notificationCenters = array();
    
    protected function __construct()
    { }
    
    public static function getNotificationCenter($name=null)
    {
        $notificationCenters = self::$notificationCenters;

        if (is_string($name)) {
            if (isset($notificationCenters[$name])) {
                return $notificationCenters[$name];
            }
            
            $newCenter = new NotificationCenter($name);
            array_push(self::$notificationCenters, $newCenter);
            return $newCenter;
        }
        
        return NotificationManager::getNotificationCenter('default');
    }
}