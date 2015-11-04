<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-05
 * Time: 3:26 PM
 */

namespace util;


use reportGenerators\AbstractGenerator;

class Formatter {

    public static function formatRank($rank) {
        if($rank == 'Tiny Tiger/Little Dragon') {
            return 'TT/LD';
        }
        return preg_replace("/\([^)]+\)/","", $rank); // 'ABC '
    }

    public static function formatAddress($data) {
        $address = '';

        return $address;
    }

    public static function formatOfficialTitle($data, $includeTitle = true) {
        $title = '';
        $title = $data[AbstractGenerator::COL_LNAME] . ', ' . $data[AbstractGenerator::COL_FNAME];
        $rank = $data[AbstractGenerator::COL_RANK];
        $gender = $data[AbstractGenerator::COL_GENDER];
        $age = 0;//\util\Converter::dobToAge($data[AbstractGenerator::COL_DOB]);
        $masters = array(
            'Sah Dan (Master)',
            'Oh Dan',
            'Yuk Dan',
            'Chil Dan',
            'Pal Dan',
            'Ku Dan',
        );
        $dans = array(
            'Cho Dan',
            'E Dan',
            'Sam Dan',
            'Sah Dan'
        );

        if($includeTitle) {
            if (in_array($rank, $masters)) {
                $title = 'Master ' . $title;
            } else if (in_array($rank, $dans)) {
                if ($gender == 'Female') {
                    if ($age < 18) {
                        $title = 'Ms. ' . $title;
                    } else {
                        $title = 'Mrs. ' . $title;
                    }
                } else {
                    $title = 'Mr. ' . $title;
                }
            }
        }
        return $title;
    }
}