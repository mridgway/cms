<?php

class Core_View_Helper_RelativeTime extends \Zend_View_Helper_Abstract
{
    /**
     * Return a date relative to now
     *
     * @param  integer|\DateTime $time
     * @return string
     */
    public function relativeTime($time)
    {
        // convert DateTime to timestamp
        if ($time instanceof \DateTime) {
            $time = $time->getTimestamp();
        }

        $timeDiff = time() - $time;

        if ($timeDiff < 1) {
            return 'Less than a second';
        }

        $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second'
                    );

        foreach ($a as $secs => $str) {
            $d = $timeDiff / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '');
            }
        }
    }
}