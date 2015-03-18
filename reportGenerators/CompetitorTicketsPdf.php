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
            if($counter > 5) {
                $counter = 1;
                $this->pdf->AddPage();
            } else {
                $this->pdf->writeHTML('<hr class="cutline">');
            }
            $helper++;
            if($helper > 20) break;
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
hr {
    margin: 3px;
}
hr.cutline {
    border: 0;
    border-bottom: 1px dashed #ccc;
    background: #999;
    margin: 10px 0;
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
        $tshirts = $this->renderTshirtList($data);;

        $html = <<<TABLE
<table style="height: 1000px;">
    <tbody>
        <tr>
            <td style="width: 65%;">
                <b>$title</b> (WTSDA #: $assocNum)<br>
                <hr style="color:$color; width: 100%; height: 7px; border: 1px solid black;">
                <br><br>
                Competition Information
                <hr>
                <b>Studio:</b> $studio <br>
                <b>Rank:</b> $rank <br>
                <b>Age:</b> $age [ $dob ] <br>
                <b>Gender:</b> $gender <br>
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

        $html = '';
        for($i = 0; $i < $adultDinnerCount; $i++) {
            $html .= '<div style="background-color: lightseagreen; width: 300px;">Adult Dinner Ticket</div>';
        }
        for($i = 0; $i < $childDinnerCount; $i++) {
            $html .= '<div style="background-color: lightgreen; width: 300px;">Child Dinner Ticket</div>';
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
        for($i = 0; $i < $tChildSCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Child Small</div>';
        }
        for($i = 0; $i < $tChildMCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Child Medium</div>';
        }
        for($i = 0; $i < $tChildLCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Child Large</div>';
        }
        for($i = 0; $i < $tAdultSCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Adult Small</div>';
        }
        for($i = 0; $i < $tAdultMCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Adult Medium</div>';
        }
        for($i = 0; $i < $tAdultLCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Adult Large</div>';
        }
        for($i = 0; $i < $tAdultXLCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Adult XL</div>';
        }
        for($i = 0; $i < $tAdultXXLCount; $i++) {
            $html .= '<div style="background-color: lightgrey; width: 300px;">T-Shirt: Adult XXL</div>';
        }
        return $html;
    }

    public function buildDataFromFile() {
        $line = 0;
        if (($handle = fopen($this->inputFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if($line == 0) {
                    $line++;
                    continue;
                }
                $lname = str_replace(' ', '', $data[self::COL_LNAME]);
                $fname = str_replace(' ', '', $data[self::COL_FNAME]);
                $this->data[$lname.'_'.$fname] = $data;
            }
            fclose($handle);
        }
        ksort($this->data);
    }

}