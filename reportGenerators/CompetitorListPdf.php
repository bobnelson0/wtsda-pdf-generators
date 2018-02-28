<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-02
 * Time: 2:51 PM
 */

namespace reportGenerators;

use util\Converter;
use util\Sorter;

require_once 'AbstractGenerator.php';

class CompetitorListPdf extends AbstractGenerator {

    public function setReportProperties() {
        // set document information
        //$this->pdf->SetCreator();
        $this->pdf->SetAuthor('WTSDA Region 1');
        $this->pdf->SetTitle('Competitor Check-in List');
        $this->pdf->SetSubject('N/A');
        $this->pdf->SetKeywords('N/A');
    }

    public function generate() {
        $this->renderHeader();
        $counter = 1;
        $helper = 1;
        foreach($this->data as $student) {
            $this->renderRow($student);
            $counter++;
            if($counter > 500) {
                $counter = 1;
                $this->pdf->AddPage();
            }
            if($helper > 500) break;
            $helper++;
        }
        $this->renderFooter();
    }

    public function renderHeader() {
        $html = <<<STYLE
<style>
body {
    font-family: sans; font-size: 8pt;
}
table, thead, tbody, tr, td {
    border: 1px solid black;
}
th {
    text-align: left;
    background-color: lightblue;
}
table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
}
td {
    padding: 2px;
    display: table-cell;
    vertical-align: top;
}
</style>
<table>
    <thead>
        <tr>
            <th scope="col" style="width:20px;"> </th>
            <th scope="col">Name</th>
            <th scope="col">Studio</th>
            <th scope="col" style="width:20px;"> </th>
            <th scope="col">Division</th>
            <th scope="col">Rank</th>
            <th scope="col">Age</th>
            <th scope="col">DOB</th>
            <th scope="col">Gender</th>
        </tr>
    </thead>
    <tbody>
STYLE;
        $this->pdf->writeHTML($html);
    }

    public function renderFooter() {
        $html = '</tr></tbody></table>';
        $this->pdf->writeHTML($html);
    }

    public function renderRow($data) {
        $title = \util\Formatter::formatOfficialTitle($data, $includeTitle = false);
        $studio = $data[self::COL_STUDIO];
        $rank = $data[self::COL_RANK];
        $color = \util\Converter::rankToColor($data[self::COL_RANK]);
        if($color == 'white') {
            $color = 'lightgrey';
        }
        $dob = $data[self::COL_DOB];
        $age = \util\Converter::dobToAge($dob);
        $gender = $data[self::COL_GENDER];
        $assocNum = \util\Converter::numToWtsdaNum($data[self::COL_WTSDA_NUM]);
        //$address = \util\Formatter::formatAddress($data);
        $phone = $data[self::COL_PHONE];
        $email = $data[self::COL_EMAIL];
        $competitor = $data[self::COL_COMPETING];
        if($competitor == 'Yes') {
            $division = $this->getDivision($data);
            //$division = 'Divisions announced during the tournament';
        } else {
            $division = 'Not competing';
            //$division = 'Divisions announced during the tournament';
        }
        $html = <<<TABLE
<tr>
    <td> </td>
    <td>$title</td>
    <td>$studio</td>
    <td style="background-color: $color;"> </td>
    <td>$division</td>
    <td>$rank</td>
    <td>$age</td>
    <td>$dob</td>
    <td>$gender</td>
</tr>
TABLE;
        $this->pdf->writeHTML($html);
    }

    public function buildDataFromFile() {
        $line = 0;
        if (($handle = fopen($this->inputFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, self::CSV_LINE_LENGTH, ",")) !== false) {
                if($line == 0) {
                    $line++;
                    continue;
                }

                $lname = str_replace(' ', '', $data[self::COL_LNAME]);
                $fname = str_replace(' ', '', $data[self::COL_FNAME]);
                $regId = $data[self::COL_REGID];
                $this->data[strtoupper($lname.'_'.$fname . '_' . $regId)] = $data;
            }
            fclose($handle);
        }
        ksort($this->data);
    }

    public function getDivision($data)
    {
        $lname = str_replace(' ', '', $data[self::COL_LNAME]);
        $fname = str_replace(' ', '', $data[self::COL_FNAME]);
        $regId = $data[self::COL_REGID];
        $sessKey = $lname.'_'.$fname . '_' . $regId;

        $divInfo = $_SESSION[$sessKey];
        if(empty($divInfo['divId']) || empty($divInfo['divName']) || $divInfo['divId'] == 'X-00') {
            return 'NO DIVISION. INFORM TOURNAMENT HOST';
        }
        return "[{$divInfo['divId']}] {$divInfo['divName']}";
    }
}