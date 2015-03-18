<?php
/**
 * User: Robert S. Nelson <bob.nelson@gmail.com>
 * Date: 2015-03-02
 * Time: 3:56 PM
 */

namespace reportGenerators;


require_once 'Converter.php';
require_once 'Sorter.php';
require_once 'Formatter.php';

abstract class AbstractGenerator {

    const COL_FNAME = 4;
    const COL_LNAME = 5;
    const COL_ADDRESS = 6;
    const COL_ADDRESS2 = 7;
    const COL_CITY = 8;
    const COL_STATE = 9;
    const COL_ZIP = 10;
    const COL_COUNTRY = 11;
    const COL_PHONE = 12;
    const COL_EMAIL = 14;
    const COL_WTSDA_NUM = 15;
    const COL_DOB = 16;
    const COL_GENDER = 17;
    const COL_RANK = 18;
    const COL_JUDGING = 19;
    const COL_STUDIO = 20;
    const COL_COMPETING = 22;
    const COL_DINNER_ADULT = 23;
    const COL_DINNER_CHILD = 24;
    const COL_TSHIRT_CHILD_S = 25;
    const COL_TSHIRT_CHILD_M = 26;
    const COL_TSHIRT_CHILD_L = 27;
    const COL_TSHIRT_ADULT_S = 28;
    const COL_TSHIRT_ADULT_M = 29;
    const COL_TSHIRT_ADULT_L = 30;
    const COL_TSHIRT_ADULT_XL = 31;
    const COL_TSHIRT_ADULT_XXL = 32;
    const COL_DATE_REGISTERED = 35;
    const COL_CONFIRM_ID = 45;


    protected $inputFile = null;
    protected $outputFile = null;
    protected $data = array();
    protected $pdf = null;

    public function __construct($input, $output) {
        $this->inputFile = $input;
        $this->outputFile = $output;

        $this->pdf = new \mPDF(
            '',    // mode - default ''
            '',    // format - A4, for example, default ''
            0,     // font size - default 0
            '',    // default font family
            15,    // margin_left
            15,    // margin right
            16,    // margin top
            16,    // margin bottom
            9,     // margin header
            9,     // margin footer
            'L');  // L - landscape, P - portrait

        $this->setReportProperties();
        $this->buildDataFromFile();
        $this->generate();

        // close and output PDF document
        $this->pdf->Output($output);
    }

    abstract public function setReportProperties();

    abstract public function generate();

    abstract public function buildDataFromFile();
}