<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-05
 * Time: 3:26 PM
 */

namespace util;


class Formatter {
    public static function formatRank($rank) {
        if($rank == 'Tiny Tiger/Little Dragon') {
            return 'TT/LD';
        }
        return preg_replace("/\([^)]+\)/","", $rank); // 'ABC '
    }
}