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
    const COL_WTSDA_NUM = 15;
    const COL_DOB = 16;
    const COL_GENDER = 17;
    const COL_RANK = 18;
    const COL_JUDGING = 19;
    const COL_STUDIO = 20;
    const COL_COMPETING = 22;

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