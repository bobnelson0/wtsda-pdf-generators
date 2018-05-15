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

class CompetitorTicketsPdf extends AbstractGenerator {

    public function setReportProperties() {
        // set document information
        //$this->pdf->SetCreator();
        $this->pdf->SetAuthor('WTSDA Region 1');
        $this->pdf->SetTitle('Competitor Tickets');
        $this->pdf->SetSubject('N/A');
        $this->pdf->SetKeywords('N/A');
    }

    public function generate() {
        $this->renderHeader();
        $counter = 1;
        $helper = 1;
        foreach($this->data as $student) {
            $this->renderTable($student);
            $counter++;
            if($counter > 4) {
                $counter = 1;
                $this->pdf->AddPage();
            } else {
                $this->pdf->writeHTML('<hr class="cutline">');
            }
            $helper++;
            //if($helper > 20) break;
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
table.white, thead.white, tbody.white, tr.white, td.white {
    border: 1px solid white;
}
td {
    padding: 2px;
    display: table-cell;
    vertical-align: top;
}
hr {
    margin: 3px;
}
hr.cutline {
    border: 0;
    border-bottom: 1px dashed #ccc;
    background: #999;
    margin: 10px 0;
}
div.extras {
    width: 1500px;
    height: 30px;
    font-size: 14px;
    position:relative;
}
div.tshirt {
    /*background-color: lightgrey;*/
    border: 1px solid black;
}
div.adultDinner {
    /*background-color: lightseagreen;*/
    border: 1px solid black;
}
div.childDinner {
    /*background-color: lightgreen;*/
    border: 1px solid black;
}
</style>
STYLE;
        $this->pdf->writeHTML($html);
    }

    public function renderFooter() {
        $html = '';
        $this->pdf->writeHTML($html);
    }

    public function renderTable($data) {
        $title = \util\Formatter::formatOfficialTitle($data);
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
        $dinners = $this->renderDinnersList($data);
        $tshirts = $this->renderTshirtList($data);
        $competitor = $data[self::COL_COMPETING];
        $regDate = date("M-d, h:ia",strtotime($data[self::COL_DATE_REGISTERED]));
        if($competitor == 'Yes') {
            //$division = $this->getDivision($data);
            $division = 'Check with tournament host';
        } else {
            $division = 'Not competing';
            //$division = 'Divisions announced during the tournament';
        }

        $this->pdf->wtsdaLogo = file_get_contents(__DIR__ . '/../assets/wtsda-logo.jpg');
        $html = <<<TABLE
<table style="height: 1000px;">
    <tbody>
        <tr>
            <td style="width: 65%;">
                <b>$title</b> <!-- (WTSDA #: $assocNum)--> (Reg. Date: $regDate)<br>
                <hr style="color:$color; width: 100%; height: 7px;">
                <br>
                Competitor Information
                <hr>
                <table class="white">
                    <tr class="white">
                        <td class="white"><b>Studio:</b> $studio</td>
                        <td class="white" rowspan="4" align="right"><img src="var:wtsdaLogo" style="width: 100px; height: 100px;"></td>
                    </tr>
                    <tr class="white">
                        <td class="white"><b>Rank:</b> $rank</td>
                    </tr>
                    <tr class="white">
                        <td class="white"><b>Age:</b> $age [ $dob ]</td>
                    </tr>
                    <tr class="white">
                        <td class="white"><b>Gender:</b> $gender</td>
                    </tr>
                    <tr class="white">
                        <td class="white"><b>Division:</b> <br/>
                        $division</td>
                    </tr>
                </table>
                <br>
                Contact Information
                <hr>
                <b>Phone:</b> $phone <br>
                <b>Email:</b> $email <br>
                <br><br>
            </td>

            <td style="width: 35%;">
            <b>Extras</b>
            $dinners
            $tshirts
            </td>
        </tr>
    </tbody>
</table>
TABLE;
        $this->pdf->writeHTML($html);
    }

    public function renderDinnersList($data) {
        $adultDinnerCount = $data[self::COL_DINNER_ADULT];
        $childDinnerCount = $data[self::COL_DINNER_CHILD];
        $childFreeDinnerCount = $data[self::COL_DINNER_CHILD_FREE];

        $html = '';
        for($i = 0; $i < $adultDinnerCount; $i++) {
            $html .= '<div class="extras adultDinner">&nbsp;&nbsp;Dinner: Adult Ticket&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $childDinnerCount; $i++) {
            $html .= '<div class="extras childDinner">&nbsp;&nbsp;Dinner: Child Ticket&nbsp;&nbsp;</div>';
        }
	    for($i = 0; $i < $childFreeDinnerCount; $i++) {
		    $html .= '<div class="extras childDinner">&nbsp;&nbsp;Dinner: Child Ticket (under 5)&nbsp;&nbsp;</div>';
	    }
        return $html;
    }

    public function renderTshirtList($data) {
        $tChildSCount = $data[self::COL_TSHIRT_CHILD_S];
        $tChildMCount = $data[self::COL_TSHIRT_CHILD_M];
        $tChildLCount = $data[self::COL_TSHIRT_CHILD_L];
        $tAdultSCount = $data[self::COL_TSHIRT_ADULT_S];
        $tAdultMCount = $data[self::COL_TSHIRT_ADULT_M];
        $tAdultLCount = $data[self::COL_TSHIRT_ADULT_L];
        $tAdultXLCount = $data[self::COL_TSHIRT_ADULT_XL];
        $tAdultXXLCount = $data[self::COL_TSHIRT_ADULT_XXL];

        $html = '';
        //return $html; // 2016, no t-shirts online
        for($i = 0; $i < $tChildSCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Child Small&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tChildMCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Child Medium&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tChildLCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Child Large&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tAdultSCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Adult Small&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tAdultMCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Adult Medium&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tAdultLCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Adult Large&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tAdultXLCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Adult XL&nbsp;&nbsp;</div>';
        }
        for($i = 0; $i < $tAdultXXLCount; $i++) {
            $html .= '<div class="extras tshirt">&nbsp;&nbsp;T-Shirt: Adult XXL&nbsp;&nbsp;</div>';
        }
        return $html;
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

    public function buildDataFromFile() {
        $line = 0;
        if (($handle = fopen($this->inputFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, self::CSV_LINE_LENGTH, ",")) !== false) {
                if($line == 0) {
                    $line++;
                    continue;
                }

                $regDate = $data[self::COL_ADDRESS];
                $lname = str_replace(' ', '', $data[self::COL_LNAME]);
                $fname = str_replace(' ', '', $data[self::COL_FNAME]);
                $regId = $data[self::COL_REGID];

                // Sort by name
                //$this->data[strtoupper($lname.'_'.$fname . '_' . $regId)] = $data;
                //$sortType = 'asc';

                // Sort by registration date (DESC)
                $this->data[$regId] = $data;
                $sortType = 'desc';
            }
            fclose($handle);
        }

        if ($sortType = 'desc') {
            krsort($this->data);
        } else {
            ksort($this->data);
        }
    }

}