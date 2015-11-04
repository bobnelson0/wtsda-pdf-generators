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

class StudioAttendancePdf extends AbstractGenerator {

    protected $studioCount = 0;
    protected $participantCount = 0;
    protected $competitorCount = 0;

    public function setReportProperties() {
        // set document information
        //$this->pdf->SetCreator();
        $this->pdf->SetAuthor('WTSDA Region 1');
        $this->pdf->SetTitle('Studio Attendance Report');
        $this->pdf->SetSubject('N/A');
        $this->pdf->SetKeywords('N/A');
    }

    public function generate() {

        $this->renderHeader();
        $this->renderCoverPage();
        foreach($this->data as $studioName => $studio) {
            $this->pdf->AddPage();
            $this->pdf->Bookmark($studioName);
            $this->pdf->WriteHTML("<h1>$studioName</h1>");
            $this->renderTable($studio);

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
}
</style>
STYLE;
        $this->pdf->writeHTML($html);
    }

    public function renderFooter() {
        $html = '';
        $this->pdf->writeHTML($html);
    }

    public function renderCoverPage() {
        $html = "<h1>2015 Region1 Championship Studio Report</h1>";
        $html .= <<<TABLEHEAD
<table>
    <thead>
        <tr>
            <th scope="col">Studios</th>
            <th scope="col">Participants</th>
            <th scope="col">Competitors</th>
        </tr>
    </thead>
    <tbody>
TABLEHEAD;
        $html .= "<tr><td>{$this->studioCount}</td><td>{$this->participantCount}</td><td>{$this->competitorCount}</td></tr>";
        $html .= '</tbody></table><br><br>';

        $html .= <<<TABLEHEAD
<table>
    <thead>
        <tr>
            <th scope="col">Studio</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
TABLEHEAD;
        foreach($this->data as $studioName => $participants) {
            $participantCount = count($participants);
            $html .= "<tr><td>$studioName</td><td>$participantCount</td></tr>";
        }
        $html .= '</tbody></table>';

        $this->pdf->WriteHTML($html);
        $this->pdf->Bookmark('Overview');
    }

    public function renderTable($data) {
        $html = <<<TABLEHEAD
<table>
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">WTSDA #</th>
            <th scope="col"> </th>
            <th scope="col">Rank</th>
            <th scope="col">Age</th>
            <th scope="col">DOB</th>
            <th scope="col">Gender</th>
            <th scope="col">Competing?</th>
            <th scope="col" width="20%">Judge?</th>
        </tr>
    </thead>
    <tbody>
TABLEHEAD;
        $data = \util\Sorter::sortStudentsForStudioReport($data);
        foreach($data as $d) {
            $html .= $this->renderRow($d);
        }
        $html .= '</tbody></table>';

        $this->pdf->writeHTML($html);
    }

    public function renderRow($data) {
        $name = $data[self::COL_LNAME] . ', ' . $data[self::COL_FNAME];
        $wtsdaNum = Converter::numToWtsdaNum($data[self::COL_WTSDA_NUM]);
        $rank = \util\Formatter::formatRank($data[self::COL_RANK]);
        $color = \util\Converter::rankToColor($data[self::COL_RANK]);
        $dob = $data[self::COL_DOB];
        $age = \util\Converter::dobToAge($dob);
        $gender = $data[self::COL_GENDER];
        $competing = $data[self::COL_COMPETING];
        $judging = $data[self::COL_JUDGING];
        $html = <<<ROW
<tr>
<td>$name</td>
<td>$wtsdaNum</td>
<td style="background-color: $color; width: 10px;"> </td>
<td>$rank</td>
<td>$age</td>
<td>$dob</td>
<td>$gender</td>
<td>$competing</td>
<td>$judging</td>
</tr>
ROW;
        return $html;
    }

    public function buildDataFromFile() {
        $line = 0;
        if (($handle = fopen($this->inputFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, self::CSV_LINE_LENGTH, ",")) !== false) {
                if($line == 0) {
                    $line++;
                    continue;
                }
                $studio = $data[self::COL_STUDIO];
                $this->data[$studio][] = $data;
                $this->participantCount++;
                if($data[self::COL_COMPETING] == 'Yes') {
                    $this->competitorCount++;
                }

            }
            fclose($handle);
        }
        ksort($this->data);
        $this->studioCount = count($this->data);
    }

}