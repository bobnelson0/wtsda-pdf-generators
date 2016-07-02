<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2016-05-24
 * Time: 11:19 AM
 */

namespace util;


class CompetitorDivisionTemplates {

    public static function getDivisions() {
        return static::get2016();
    }

    public static function get2016() {
        return array(
            /** TT/LD Divisions **/
            'all:TTLD00:TTLD00:0:8:A-01',


            /** Gup Divisions **/
            // Beginners
            'male:WHITE10:ORANGE07:0:7:G-01',
            'male:WHITE10:ORANGE07:8:8:G-02',
            'female:WHITE10:ORANGE07:0:8:G-03',

            'male:WHITE10:ORANGE07:9:12:G-04',
            'female:WHITE10:ORANGE07:9:12:G-05',

            'male:WHITE10:ORANGE07:13:17:G-06', // Zero
            'female:WHITE10:ORANGE07:13:17:G-07', // Zero

            'male:WHITE10:ORANGE07:18:120:G-08', // Combine?
            'female:WHITE10:ORANGE07:18:120:G-09', // Zero

            // Intermediate
            'male:GREEN06:BROWN03:0:8:G-10',
            'female:GREEN06:BROWN03:0:8:G-11',

            'male:GREEN06:GREEN05:9:12:G-12',
            'male:BROWN04:BROWN03:9:12:G-13',
            'female:GREEN06:GREEN05:9:12:G-14',
            'female:BROWN04:BROWN03:9:12:G-15',

            'male:GREEN06:BROWN03:13:17:G-16',
            'female:GREEN06:BROWN03:13:17:G-17',

            'male:GREEN06:BROWN03:18:120:G-18',
            'female:GREEN06:BROWN03:18:120:G-19',

            // Advanced
            'male:RED02:CDB00:0:8:G-20', // Combine?
            'female:RED02:CDB00:0:8:G-21', // Combine?

            'male:RED02:CDB00:9:11:G-22',
            'male:RED02:CDB00:12:12:G-23',
            'female:RED02:CDB00:9:12:G-24',

            'male:RED02:CDB00:13:13:G-25',
            'male:RED02:CDB00:14:17:G-26',
            'female:RED02:CDB00:13:13:G-27',
            'female:RED02:CDB00:14:17:G-28',

            'male:RED02:CDB00:18:120:G-29',
            'female:RED02:CDB00:18:120:G-30',


            /** Dans Divisions **/
            'male:DAN01:DAN03:0:17:D-01',
            'female:DAN01:DAN03:0:17:D-02',

            'male:DAN01:DAN03:18:34:D-03',
            'female:DAN01:DAN03:18:34:D-04',

            'male:DAN01:DAN03:35:120:D-05',
            'female:DAN01:DAN03:35:120:D-06',
        );
    }

    public static function get2015() {
        return array(
            // TTLD Divisions
            'all:TTLD00:TTLD00:0:8:G-01',

            /* Gup Divisions */
            'female:WHITE10:GREEN05:0:8:G-02',
            'male:WHITE10:ORANGE07:0:8:G-03',
            'male:WHITE10:ORANGE07:9:12:G-04',
            'female:WHITE10:GREEN05:17:120:G-05',
            'male:WHITE10:GREEN05:17:120:G-06',

            'male:GREEN06:BROWN04:0:8:G-07', //3rd gup?
            'male:GREEN06:GREEN05:9:11:G-08',
            'female:GREEN06:BROWN03:12:13:G-09', //used to be 10-13. Cece?
            'male:GREEN06:BROWN03:12:13:G-10',

            'female:BROWN04:RED01:7:10:G-11',
            //G12-Brown 11: Red 11-12: Girls
            'female:BROWN04:BROWN03:11:11:G-12a', //used to be all red
            'female:RED02:RED01:11:12:G-12b', //used to be all red
            'male:BROWN04:RED01:9:11:G-13', // used to be all brown

            'female:RED02:CDB00:13:16:G-14',
            'male:RED02:RED01:12:16:G-15a', // special group?
            'male:CDB00:CDB00:13:14:G-15b', // special group?

            'female:BROWN04:RED01:17:120:G-16',
            'male:BROWN04:RED01:17:120:G-17',

            'female:CDB00:CDB00:9:12:G-18',
            'male:CDB00:CDB00:7:12:G-19',
            //'male:RED02:CDB00:12:12',
            'female:CDB00:CDB00:17:120:G-20',
            'male:CDB00:CDB00:17:120:G-21',


            // Dans Divisions
            'male:DAN01:DAN03:13:16:D-01',
            'female:DAN01:DAN03:13:16:D-02',

            'male:DAN01:DAN03:17:34:D-03',
            'female:DAN01:DAN03:17:34:D-04',

            'male:DAN01:DAN03:35:120:D-05',
            'female:DAN01:DAN03:35:120:D-06',
        );
    }

    public static function getDefault() {
        return array(

            /** TT/LD Divisions **/
            'all:TTLD00:TTLD00:0:8:TTLD-01',


            /** Gup Divisions **/
            // Beginners
            'male:WHITE10:ORANGE07:0:8:G-01',
            'female:WHITE10:ORANGE07:0:8:G-02',

            'male:WHITE10:ORANGE07:9:12:G-03',
            'female:WHITE10:ORANGE07:9:12:G-04',

            'male:WHITE10:ORANGE07:13:17:G-05',
            'female:WHITE10:ORANGE07:13:17:G-06',

            'male:WHITE10:ORANGE07:18:120:G-07',
            'female:WHITE10:ORANGE07:18:120:G-08',

            // Intermediate
            'male:GREEN06:BROWN03:0:8:G-09',
            'female:GREEN06:BROWN03:0:8:G-10',

            'male:GREEN06:BROWN03:9:12:G-11',
            'female:GREEN06:BROWN03:9:12:G-12',

            'male:GREEN06:BROWN03:13:17:G-13',
            'female:GREEN06:BROWN03:13:17:G-14',

            'male:GREEN06:BROWN03:18:120:G-15',
            'female:GREEN06:BROWN03:18:120:G-16',

            // Advanced
            'male:RED02:CDB00:0:8:G-17',
            'female:RED02:CDB00:0:8:G-18',

            'male:RED02:CDB00:9:12:G-19',
            'female:RED02:CDB00:9:12:G-20',

            'male:RED02:CDB00:13:17:G-21',
            'female:RED02:CDB00:13:17:G-22',

            'male:RED02:CDB00:18:120:G-23',
            'female:RED02:CDB00:18:120:G-24',


            /** Dans Divisions **/
            'male:DAN01:DAN03:13:17:D-01',
            'female:DAN01:DAN03:13:17:D-02',

            'male:DAN01:DAN03:18:34:D-03',
            'female:DAN01:DAN03:18:34:D-04',

            'male:DAN01:DAN03:35:120:D-05',
            'female:DAN01:DAN03:35:120:D-06',
        );
    }
}