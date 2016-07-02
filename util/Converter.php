<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-05
 * Time: 10:19 AM
 */

namespace util;


class Converter {

    const RANK_TTLD00    = 1;
    const RANK_WHITE10   = 2;
    const RANK_WHITE09   = 3;
    const RANK_ORANGE08  = 4;
    const RANK_ORANGE07  = 5;
    const RANK_GREEN06   = 6;
    const RANK_GREEN05   = 7;
    const RANK_BROWN04   = 8;
    const RANK_BROWN03   = 9;
    const RANK_RED02     = 10;
    const RANK_RED01     = 11;
    const RANK_CDB00     = 12;
    const RANK_DAN01     = 13;
    const RANK_DAN02     = 14;
    const RANK_DAN03     = 15;
    const RANK_MASTER    = 999;

    public static function dobToAge($dob) {
        try {
            $dob = explode('/', $dob);
            $from = new \DateTime();
            if(strlen($dob[2]) == 2) {
                if ($dob[2] <= 99 && $dob[2] >= (date('y') - 4)) {
                    $dob[2] = '19' . $dob[2];
                } else {
                    $dob[2] = '20' . $dob[2];
                }
            }
            $from->setDate($dob[2], $dob[0], $dob[1]);
            $to = new \DateTime();
            return $from->diff($to)->y;
        } catch (\Exception $e) {
            return 0;
        }
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

    public static function rankToNumber($rank) {
        switch ($rank) {
            case 'Tiny Tiger/Little Dragon': return self::RANK_TTLD00;
            case '10th Gup (White)': return self::RANK_WHITE10;
            case '9th Gup (White w/ Stripe)': return self::RANK_WHITE09;
            case '8th Gup (Orange)': return self::RANK_ORANGE08;
            case '7th Gup (Orange w/ Stripe)': return self::RANK_ORANGE07;
            case '6th Gup (Green)': return self::RANK_GREEN06;
            case '5th Gup (Green w/ Stripe)': return self::RANK_GREEN05;
            case '4th Gup (Brown)': return self::RANK_BROWN04;
            case '3rd Gup (Brown w/ Stripe)': return self::RANK_BROWN03;
            case '2nd Gup (Red)': return self::RANK_RED02;
            case '1st Gup (Red w/ Stripe)': return self::RANK_RED01;
            case 'Cho Dan Bo (Dark Blue)': return self::RANK_CDB00;
            case 'Cho Dan': return self::RANK_DAN01;
            case 'E Dan': return self::RANK_DAN02;
            case 'Sam Dan': return self::RANK_DAN03;
            default: return self::RANK_MASTER;
        }
    }

    public static function numberToRank($number) {
        switch ($number) {
            case self::RANK_TTLD00: return 'Tiny Tiger/Little Dragon';
            case self::RANK_WHITE10: return '10th Gup (White)';
            case self::RANK_WHITE09: return '9th Gup (White w/ Stripe)';
            case self::RANK_ORANGE08: return '8th Gup (Orange)';
            case self::RANK_ORANGE07: return '7th Gup (Orange w/ Stripe)';
            case self::RANK_GREEN06: return '6th Gup (Green)';
            case self::RANK_GREEN05: return '5th Gup (Green w/ Stripe)';
            case self::RANK_BROWN04: return '4th Gup (Brown)';
            case self::RANK_BROWN03: return '3rd Gup (Brown w/ Stripe)';
            case self::RANK_RED02: return '2nd Gup (Red)';
            case self::RANK_RED01: return '1st Gup (Red w/ Stripe)';
            case self::RANK_CDB00: return 'Cho Dan Bo (Dark Blue)';
            case self::RANK_DAN01: return 'Cho Dan';
            case self::RANK_DAN02: return 'E Dan';
            case self::RANK_DAN03: return 'Sam Dan';
            default: return 'Master';
        }
    }

    public static function getRankNumberFromConst($const) {
        return constant('self::' . $const);
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