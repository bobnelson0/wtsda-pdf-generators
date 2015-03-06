<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-05
 * Time: 10:19 AM
 */

namespace util;


class Converter {

    public static function dobToAge($dob) {
        $from = new \DateTime($dob);
        $to   = new \DateTime('today');
        return $from->diff($to)->y;
    }

    public static function rankToColor($rank) {
        $rank = strtolower($rank);

        if(strpos($rank, 'cho dan bo') !== false) {
            $color =  'blue';
        } else if(strpos($rank, 'dan') !== false) {
            $color =  'black';
        } else if(strpos($rank, 'red') !== false) {
            $color =  'red';
        } else if(strpos($rank, 'brown') !== false) {
            $color =  'saddlebrown';
        } else if(strpos($rank, 'green') !== false) {
            $color =  'green';
        } else if(strpos($rank, 'orange') !== false) {
            $color =  'darkorange';
        } else {
            $color =  'white';
        }
        return $color;
    }

    public static function numToWtsdaNum($num) {
        if(is_int($num) || intval($num) !== 0) {
            if(strlen($num) == 6) {
                return $num;
            } else if(strlen($num) < 6){
                return '0' . $num;
            }
        } return 'INVALID';
    }
}