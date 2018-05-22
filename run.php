<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-02
 * Time: 3:49 PM
 */
date_default_timezone_set('America/Los_Angeles');

$start = time();
session_start();

$path = './util';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

error_reporting(-1);
ini_set('display_errors', 'On');

require_once './vendor/autoload.php';
require_once './reportGenerators/StudioAttendancePdf.php';
require_once './reportGenerators/CompetitorTicketsPdf.php';
require_once './reportGenerators/CompetitorListPdf.php';
require_once './reportGenerators/CompetitorsPerDivisionPdf.php';
require_once './reportGenerators/DinnerAttendanceListPdf.php';
require_once './reportGenerators/UnpaidAttendeeListPdf.php';

// Set Date
$date = date('Y-m-d_h.ia');

// Run generator here
echo "Generating studio attendance report ($date)\n";
$report = new \reportGenerators\StudioAttendancePdf('./data/input.csv', './data/Studio.Attendance.' . $date . '.pdf');

// Run generator here
echo "Generating divisions report ($date)\n";
$report = new \reportGenerators\CompetitorsPerDivisionPdf('./data/input.csv', './data/Competitor.Divisions.' . $date . '.pdf');

// Run generator here
echo "Generating competitor list ($date)\n";
$report = new \reportGenerators\CompetitorListPdf('./data/input.csv', './data/Competitor.List.' . $date . '.pdf');

// Run generator here
echo "Generating tickets Alphabetical ($date)\n";
$report = new \reportGenerators\CompetitorTicketsPdf(
	'./data/input.csv',
	'./data/Competitor.Tickets.Alphabetical.' . $date . '.pdf',
	\reportGenerators\CompetitorTicketsPdf::SORT_ALPHA);

// Run generator here
echo "Generating tickets by Reg Date($date)\n";
$report = new \reportGenerators\CompetitorTicketsPdf(
	'./data/input.csv',
	'./data/Competitor.Tickets.RegDate.' . $date . '.pdf',
	\reportGenerators\CompetitorTicketsPdf::SORT_REGDATE);

// Run generator here
echo "Generating dinner attendees report ($date)\n";
$report = new \reportGenerators\DinnerAttendanceListPdf('./data/input.csv', './data/Dinner.Attendance.List.' . $date . '.pdf');

// Run generator here
echo "Generating unpaid report ($date)\n";
$report = new \reportGenerators\UnpaidAttendeeListPdf('./data/input.csv', './data/Unpaid.Attendee.List.' . $date . '.pdf');

// Clean up
session_destroy();
$elapsed = time() - $start;

$hours = floor($elapsed / 3600);
$minutes = floor(($elapsed / 60) % 60);
$seconds = $elapsed % 60;

echo "Total time: $hours:$minutes:$seconds\n";