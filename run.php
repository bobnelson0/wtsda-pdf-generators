<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-02
 * Time: 3:49 PM
 */
$path = './util';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once './vendor/autoload.php';
require_once './reportGenerators/StudioAttendancePdf.php';

//Run generator here
$date = date('Y-m-d_H.i.s');
$report = new \reportGenerators\StudioAttendancePdf('./data/input.csv', './data/StudioAttendance_' . $date . '.pdf');
