<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-04-29
 * Time: 2:14 PM
 */

namespace reportGenerators;

use App\Util\Convert;
use util\CompetitorDivisionTemplates;
use util\Converter;
use util\Sorter;

require_once 'AbstractGenerator.php';

class CompetitorsPerDivisionPdf extends AbstractGenerator {

    protected $divisions = array();

    public function setReportProperties() {
        // set document information
        //$this->pdf->SetCreator();
        $this->pdf->SetAuthor('WTSDA Region 1');
        $this->pdf->SetTitle('Divisions');
        $this->pdf->SetSubject('N/A');
        $this->pdf->SetKeywords('N/A');
    }

    public function generate()
    {
        usort($this->divisions, function($c1, $c2) {
            return strcmp($c1['id'], $c2['id']);
        });
        $this->renderHeader();
        $this->renderCoverPage();
        foreach($this->divisions as $divKey => $division) {
            $competitorCount = count($division['competitors']);
            $this->pdf->AddPage();
            $this->pdf->Bookmark("{$division['id']} : {$division['name']}");
            $this->pdf->WriteHTML("<h1>{$division['id']} : {$division['name']}<br>$competitorCount competitors</h1>");
            $this->renderTable($division['competitors']);
        }
        $this->pdf->WriteHTML("<br>Duplicate info:<br>");
        if(is_array($_SESSION['doubles'])) {
            foreach ($_SESSION['doubles'] as $key => $value) {
                $this->pdf->WriteHTML("$key : $value<br>");
            }
        }
        $this->renderFooter();
    }

    public function renderHeader() {
        $html = <<<STYLE
<style>
body {
    font-family: sans; font-size: 11pt;
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
        $html = "<h1>" . date("Y") ." WTSDA:R1 Championship Divisions</h1>";

        $html .= <<<TABLEHEAD
<table style="font-size:13pt;">
    <thead>
        <tr>
            <th scope="col" style="width:20px;"> </th>
            <th scope="col" style="width:50px;">ID</th>
            <th scope="col">Division</th>
            <th scope="col" style="width:140px;">Competitors</th>
        </tr>
    </thead>
    <tbody>
TABLEHEAD;
        foreach($this->divisions as $divKey => $division) {
            $competitorCount = count($division['competitors']);
            if($competitorCount <= 2 || $competitorCount >= 12) $color = 'red';
            elseif($competitorCount <= 3 || $competitorCount >= 11) $color = 'yellow';
            else $color = 'white';

            if($division['id'] == 'X-00' || $division['id'] == 'X-01') {
                $color = 'white';
                if($competitorCount > 0) {
                    $color = 'red';
                }
            }

            if($division['id'] == 'N-00') {
                $color = 'white';
            }
            $color = 'white';

            $html .= "<tr style=\"background-color: $color;\"><td></td><td>{$division['id']}</td><td>{$division['name']}</td><td>$competitorCount</td></tr>";
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
            <th scope="col" style="width:20px;"> </th>
            <th scope="col">Name</th>
            <th scope="col">Studio</th>
            <th scope="col">Rank</th>
            <th scope="col">Age</th>
            <th scope="col">DOB</th>
            <th scope="col">Gender</th>
        </tr>
    </thead>
    <tbody>
TABLEHEAD;
        $data = \util\Sorter::sortStudentsForStudioReport($data);
        if(!empty($data)) {
            foreach($data as $d) {
                $html .= $this->renderRow($d);
            }
        }
        $html .= '</tbody></table>';

        $this->pdf->writeHTML($html);
    }

    public function renderRow($data) {
        $name = $data[self::COL_LNAME] . ', ' . $data[self::COL_FNAME];
        $studio = $data[self::COL_STUDIO];
        $rank = \util\Formatter::formatRank($data[self::COL_RANK]);
        $dob = $data[self::COL_DOB];
        $age = \util\Converter::dobToAge($dob);
        $gender = $data[self::COL_GENDER];
        $html = <<<ROW
<tr>
<td> </td>
<td>$name</td>
<td>$studio</td>
<td>$rank</td>
<td>$age</td>
<td>$dob</td>
<td>$gender</td>
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
                $line++;
                $lname = $data[self::COL_LNAME];
                $fname = $data[self::COL_FNAME];
                $gender = strtolower($data[self::COL_GENDER]);
                $rank = Converter::rankToNumber($data[self::COL_RANK]);
                $age = Converter::dobToAge($data[self::COL_DOB]);
                $regId = $data[self::COL_REGID];
                $this->data["$gender:$rank:$age:$regId"] = $data;
                $_SESSION["{$lname}_{$fname}_{$regId}_info"] = $data;
            }
            fclose($handle);
        }

        //ksort($this->data);
        //print_r($this->data);
        //exit;

        $this->sortIntoDivisions();
        //print_r($this->divisions);
        //exit;

    }

    public function sortIntoDivisions() {
        $this->divisions = $this->getDivisions();

        foreach($this->data as $key => $competitor) {
            $tmp = array();
            $parts = explode(':', $key);
            $gender = $parts[0];
            $rank = $parts[1];
            $age = $parts[2];

            $lname = str_replace(' ', '', $competitor[self::COL_LNAME]);
            $fname = str_replace(' ', '', $competitor[self::COL_FNAME]);
            $regId = $competitor[self::COL_REGID];
            $sessKey = $lname.'_'.$fname . '_' . $regId;

            if($rank == 999 || $competitor[self::COL_COMPETING] == 'No') {
                $this->divisions['non-competitors']['competitors'][] = $competitor;
                $divInfo = array(
                    'divId' => $this->divisions['non-competitors']['id'],
                    'divName' => $this->divisions['non-competitors']['name']
                );
                continue;
            } else {
                $divKey = 'unassigned';
                $count = 0;
                foreach ($this->divisions as $k => $div) {
                    if ($k == 'unassigned' || $k == 'non-competitors' || $k == 'doubles') continue;

                    $p = explode(':', $k);
                    $divGender = $p[0];
                    //var_dump('RANK_' . $p[1]);
                    //var_dump('RANK_' . $p[2]);
                    $divRankMin = Converter::getRankNumberFromConst('RANK_' . $p[1]);
                    $divRankMax = Converter::getRankNumberFromConst('RANK_' . $p[2]);
                    $divAgeMin = $p[3];
                    $divAgeMax = $p[4];

                    if ($divGender != 'all') {
                        if ($gender != $divGender) continue;
                    }
                    if ($rank < $divRankMin) continue;
                    if ($rank > $divRankMax) continue;
                    if ($age < $divAgeMin) continue;
                    if ($age > $divAgeMax) continue;
                    $divKey = $k;
                    $tmp[] = $k;
                    $count++;
                }
                if($count > 1) {
                    $t = json_encode($tmp);
                    $_SESSION['doubles'][$sessKey] = $t;
                    echo "Double assignment: $sessKey: $t \n";
                }
                $this->divisions[$divKey]['competitors'][] = $competitor;
                $divInfo = array(
                    'divId' => $this->divisions[$divKey]['id'],
                    'divName' => $this->divisions[$divKey]['name']
                );
            }
            $_SESSION[$sessKey] = $divInfo;
        }

        // Special divisions
        //G-15
        /*$this->divisions['male:RED02:CDB00:12:16'] = array(
            'id' => 'G-15',
            'name' => 'Boys: Red: Ages 12-16/Blue: Ages 13-14',
            'competitors' => array_merge(
                $this->divisions['male:RED02:RED01:12:16:G-15a']['competitors'],
                $this->divisions['male:CDB00:CDB00:13:14:G-15b']['competitors']
            )
        );
        foreach($this->divisions['male:RED02:CDB00:12:16']['competitors'] as $comp) {
            $lname = str_replace(' ', '', $comp[self::COL_LNAME]);
            $fname = str_replace(' ', '', $comp[self::COL_FNAME]);
            $regId = $comp[self::COL_REGID];
            $sessKey = $lname.'_'.$fname . '_' . $regId;
            $divInfo = array(
                'divId' => $this->divisions['male:RED02:CDB00:12:16']['id'],
                'divName' => $this->divisions['male:RED02:CDB00:12:16']['name']
            );
            $_SESSION[$sessKey] = $divInfo;
        }
        unset($this->divisions['male:RED02:RED01:12:16:G-15a']);
        unset($this->divisions['male:CDB00:CDB00:13:14:G-15b']);


        // G12-Brown 11: Red 11-12: Girls
        //'female:BROWN04:BROWN03:11:11:G-12a', //used to be all red
        //'female:RED02:RED01:11:12:G-12b', //used to be all red
        $this->divisions['female:BROWN04:RED01:11:12'] = array(
            'id' => 'G-12',
            'name' => 'Girls : Brown: Ages 11/Red: Ages 11-12',
            'competitors' => array_merge(
                $this->divisions['female:BROWN04:BROWN03:11:11:G-12a']['competitors'],
                $this->divisions['female:RED02:RED01:11:12:G-12b']['competitors']
            )
        );
        foreach($this->divisions['female:BROWN04:RED01:11:12']['competitors'] as $comp) {
            $lname = str_replace(' ', '', $comp[self::COL_LNAME]);
            $fname = str_replace(' ', '', $comp[self::COL_FNAME]);
            $regId = $comp[self::COL_REGID];
            $sessKey = $lname.'_'.$fname . '_' . $regId;
            $divInfo = array(
                'divId' => $this->divisions['female:BROWN04:RED01:11:12']['id'],
                'divName' => $this->divisions['female:BROWN04:RED01:11:12']['name']
            );
            $_SESSION[$sessKey] = $divInfo;
        }
        unset($this->divisions['female:BROWN04:BROWN03:11:11:G-12a']);
        unset($this->divisions['female:RED02:RED01:11:12:G-12b']);
        */

        if(is_array($_SESSION['doubles'])) {
            foreach ($_SESSION['doubles'] as $key => $value) {
                $this->divisions['doubles']['competitors'][] = $_SESSION[$key . "_info"];
            }
        }
    }

    public function getDivisions()
    {
        $defintions = $this->getDivisionDefinitions();
        $divisions = array();
        $danCount = 1;
        $gupCount = 1;

        foreach($defintions as $definition) {
            $defs = explode(':', $definition);
            $divGender = $defs[0];
            $divRankMin = $defs[1];
            $divRankMax = $defs[2];
            $divAgeMin = $defs[3];
            $divAgeMax = $defs[4];
            $id = $defs[5];

            /*if(strpos($divRankMin, 'DAN') !== false && strpos($divRankMax, 'DAN') !== false) {
                $id = 'D-' . str_pad($danCount, 2, "0", STR_PAD_LEFT);
                $danCount++;
            } else {
                $id = 'G-' . str_pad($gupCount, 2, "0", STR_PAD_LEFT);
                $gupCount++;
            }*/

            $name = $this->generateDivisionName($divGender, $divRankMin, $divRankMax, $divAgeMin, $divAgeMax);

            $divisions[$definition] = array(
                'id' => $id,
                'name' => $name,
                'competitors' => array()
            );
        }

        /*
         * This is where all non-competitors are placed
         */
        $divisions['non-competitors'] = array(
            'id' => 'N-00',
            'name' => 'Non-Competitor',
            'competitors' => array()
        );

        /*
         * This is where people are dumped if they don't match a division
         */
        $divisions['unassigned'] = array(
            'id' => 'X-00',
            'name' => 'Unassigned',
            'competitors' => array()
        );

        /*
         * This is where doubles go
         */
        $divisions['doubles'] = array(
            'id' => 'X-01',
            'name' => 'More than one division',
            'competitors' => array()
        );
        return $divisions;
    }

    public function generateDivisionName($g, $rmin, $rmax, $amin, $amax)
    {
        if($amax >= 18) {
            $allLabel = 'Men & Women';
            $maleLabel = 'Men';
            $femaleLabel = 'Women';
        } else {
            $allLabel = 'Boys & Girls';
            $maleLabel = 'Boys';
            $femaleLabel = 'Girls';
        }
        $gender = ${$g . "Label"};

        if($rmin == 'DAN01' && $rmax == 'DAN03') {
            $ranks = 'All Dans';
        } else if($rmin == $rmax) {
            $ranks = Converter::numberToRank(Converter::getRankNumberFromConst('RANK_'.$rmin));
        } else {
            $rmin = current(explode(" (", Converter::numberToRank(Converter::getRankNumberFromConst('RANK_'.$rmin)), 2));
            $rmax = current(explode(" (", Converter::numberToRank(Converter::getRankNumberFromConst('RANK_'.$rmax)), 2));
            $ranks = "$rmin - $rmax";
        }

        if($amin == 0) {
            $ages = "$amax & Younger";
        } else if($amax == 120) {
            $ages = "$amin & Older";
        } else {
            $ages = "$amin-$amax";
        }

        $name = "$gender : $ranks : Ages $ages";

        return $name;
    }

    public function getDivisionDefinitions()
    {
        return CompetitorDivisionTemplates::getDivisions();
    }
}