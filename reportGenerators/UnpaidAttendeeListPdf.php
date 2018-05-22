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

class UnpaidAttendeeListPdf extends AbstractGenerator {

    public function setReportProperties() {
        // set document information
        //$this->pdf->SetCreator();
        $this->pdf->SetAuthor('WTSDA Region 1');
        $this->pdf->SetTitle('Unpaid Attendee List');
        $this->pdf->SetSubject('N/A');
        $this->pdf->SetKeywords('N/A');
    }

    public function generate() {
        $this->renderHeader();
        $counter = 1;
        $helper = 1;
        $altColor = false;
        foreach($this->data as $student) {
            $this->renderRow($student, $altColor);
	        $altColor = !$altColor;
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

	    $year = date("Y");
        $html = <<<STYLE
<style>
body {
    font-family: sans; font-size: 8pt;
}
table, thead, tbody, tr, td {
    border: 1px solid black;
}
tr.alt {
    background-color: lightgrey;
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
<h1>$year WTSDA:R1 Unpaid Attendee List</h1>
<table>
    <thead>
        <tr>
            <th scope="col" style="width:20px;text-align: center">&#10004;</th>
            <th scope="col">Name</th>
            <th scope="col">Studio</th>
            <!-- <th scope="col" style="width:20px;"> </th> -->
            <th scope="col">Amount Owed</th>
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

    public function renderRow($data, $altColor = false) {
        $title = \util\Formatter::formatOfficialTitle($data, $includeTitle = false);
        $studio = $data[self::COL_STUDIO];
        $amountOwed = $data[self::COL_AMOUNT_OWED];
        // For 2018 only
        if ($amountOwed == '$75.00') {
        	$amountOwed = '$65.00';
        }

	    $trClass = '';
	    if ($altColor) {
		    $trClass = 'class="alt"';
	    }
        $html = <<<TABLE
<tr $trClass>
    <td> </td>
    <td>$title</td>
    <td>$studio</td>
    <td>$amountOwed</td>
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

                // Skip paid people
	            if (trim($data[self::COL_STATUS]) != 'REGISTERED (UNPAID)') {
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
}