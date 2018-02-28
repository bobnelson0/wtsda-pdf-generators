<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-05
 * Time: 1:00 PM
 */

namespace util;


use reportGenerators\AbstractGenerator;

class Sorter {

    public static function sortStudentsForStudioReport($data) {
        usort($data, function($first, $second) {
            $rankFirst = $first[AbstractGenerator::COL_RANK];
            $rankSecond = $second[AbstractGenerator::COL_RANK];
            $nameFirst = strtoupper($first[AbstractGenerator::COL_LNAME].$first[AbstractGenerator::COL_FNAME]);
            $nameSecond = strtoupper($second[AbstractGenerator::COL_LNAME].$second[AbstractGenerator::COL_FNAME]);

            return strcmp($nameFirst, $nameSecond);
            $compRanks = strcmp($rankFirst, $rankSecond);
            if($compRanks == 0) {
                return strcmp($nameFirst, $nameSecond);
            } else {
                return $compRanks;
            }
        });
        return $data;
    }
}