<?php
/**
 * Main file
 *
 * All functions for XML writing
 *
 * @category Controller
 * @package PurePhpXmlWriter
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 *
 */

namespace PurePhpXmlWriter;


class PurePhpXmlWriter
{

    /**
     * XML file pointer
     *
     * @var resource
     */
    private $_filePointer;

    /**
     * XML file name
     *
     * @var string
     */
    private $_fileName;

    /**
     * File encoding
     *
     * @var string
     */
    private $_encoding;

    /**
     * Prefix of file name (used only with temporary files)
     *
     * @var string
     */
    private $_fileNamePrefix;

    /**
     * Write mode for file
     *
     * @var string
     * @see https://secure.php.net/manual/en/function.fopen.php
     */
    private $_writeMode;

    /**
     * Indicator if EOL should be used
     *
     * @var bool
     */
    private $_isCompressed;

    /**
     * XML file header
     *
     * @var string
     */
    private $_fileHeader;

    /**
     * Indicates how deep child element is
     *
     * @var int
     */
    private $_elementDeep = 0;

    /**
     * Setter for file name
     *
     * @param $filename
     */
    public function setFileName($filename){
        $this->_fileName = $filename;
    }

    /**
     * Setter for encoding
     *
     * @param $encoding
     */
    public function setEncoding($encoding){
       $this->_encoding = $encoding;
    }

    /**
     * Setter for file name prefix (for generated names only)
     *
     * @param $fileNamePrefix
     */
    public function setFileNamePrefix($fileNamePrefix){
        $this->_fileNamePrefix = $fileNamePrefix;
    }

    /**
     * Setter for write mode
     *
     * @param $writeMode
     * @see https://secure.php.net/manual/en/function.fopen.php
     */
    public function setWriteMode($writeMode){
        $this->_writeMode = $writeMode;
    }

    /**
     * Setter for compressed format (EOL used or not)
     *
     * @param $compressed bool
     */
    public function setIsCompressed($isCompressed){
        $this->_isCompressed = $isCompressed;
    }


    /**
     * Set file header
     *
     * @param string $fileHeader
     */
    public function setHeader($fileHeader = null){

        /**
         * Set standard XML header if not defined
         */
        if($fileHeader === null){
            $this->_fileHeader = '<?xml version="1.0" encoding="' . $this->_encoding .'"?>';
        }
        else{
            $this->_fileHeader = $fileHeader;
        }
    }

    /**
     * Getter for file name
     *
     * @return string
     */
    public function getFileName(){
        return $this->_fileName;
    }

    /**
     * Constructor
     *
     * @param string $fileName
     * @param bool $autoOpenFile Indicates if file should be opened immediately
     * @param string $encoding
     * @param string $fileNamePrefix
     * @param string $writeMode
     * @param bool $compressed Indicates if EOL should be used or not
     */
    public function __construct($fileName = null, $autoOpenFile = false, $encoding = 'utf-8', $fileNamePrefix = 'ppxw',
        $writeMode = 'w', $isCompressed = false)
    {
        /**
         * Setters
         */
        $this->setEncoding($encoding);
        $this->setFileNamePrefix($fileNamePrefix);
        $this->setWriteMode($writeMode);
        $this->setIsCompressed($isCompressed);
        $this->setHeader();

        /**
         * Generate filename if needed
         */
        if($fileName === null){
            $this->setFileName(tempnam(sys_get_temp_dir(), $this->_fileNamePrefix));
        }
        else{
            $this->setFileName($fileName);
        }

        /**
         * Autoopen file if needed
         */
        if($autoOpenFile === true) {
            $this->openFile();
        }
    }

    /**
     * Destructor
     *
     * Explicitly close the file to make a sure buffer has been written
     */
    public function __destruct()
    {
        $this->closeFile();
    }

    /**
     * Open XML file
     */
    public function openFile($autoAddHeader = true){
        try {
            $this->_filePointer = fopen($this->_fileName, $this->_writeMode);
        }
        catch (\Exception $e){
            die('File cannot be opened: ' . $e->getMessage());
        }

        if($autoAddHeader === true){
            $this->writeHeader();
        }
    }

    /**
     * Close XML file if is opened
     */
    public function closeFile(){
        if(is_resource($this->_filePointer) === true){
            fclose($this->_filePointer);
        }
    }

    /**
     * Write the EOL if file is not compressed
     */
    private function _writeEol(){
        if($this->_isCompressed === false){
            fwrite($this->_filePointer, PHP_EOL);
        }
    }

    /**
     * Write the Tabs if file is not compressed
     */
    private function _writeTabs(){
        if($this->_isCompressed === false){
            for($a = 0; $a < $this->_elementDeep; $a++) {
                fwrite($this->_filePointer, "\t");
            }
        }
    }

    /**
     * Write string to file
     *
     * @param string $string
     * @param bool $useTabs Indicates if use tabs
     * @param bool $useEol Indicates if use EOL
     */
    private function _writeString($string = '', $useTabs = true, $useEol = true){
        if($useTabs === true){
            $this->_writeTabs();
        }

        fwrite($this->_filePointer, $string);

        if($useEol === true){
            $this->_writeEol();
        }
    }

    /**
     * Write file header
     */
    public function writeHeader(){
        $this->_writeString($this->_fileHeader, false, true);
    }

    /**
     * Open XML element
     *
     * @param $tag
     * @param bool $expectedChildren Indicates if children are expected
     */
    public function openXMLElement($tag, $expectedChildren = true)
    {
        $this->_writeString('<' . $tag . '>', true, $expectedChildren);
        $this->_elementDeep++;
    }

    /**
     * Close XML element
     *
     * @param $tag
     * @param bool $expectedChildren Indicates if children are expected
     */
    public function closeXMLElement($tag, $expectedChildren = true)
    {
        $this->_elementDeep--;
        $this->_writeString('</' . $tag . '>', $expectedChildren,true);
    }

    /**
     * Write blank XML element
     *
     * @param $tag
     */
    public function blankXMLElement($tag)
    {
        $this->_writeString('<' . $tag . '/>', true,true);

    }

    /**
     * Save element with value
     *
     * @param $tag
     * @param $value
     * @param int $decimals Only using with number
     * @param bool $useCdata
     * @param bool $forceCdata
     */
    public function saveElementWithValue($tag, $value, $decimals = 0, $useCdata = true, $forceCdata = false){

        /**
         * Empty element
         */
        if (empty($value)) {
            $this->blankXMLElement($tag);
        }
        else {

            /**
             * Non-empty element
             */
            if ($useCdata === true && (is_numeric($value) === false || $forceCdata === true)) {
                if (is_numeric($value) == true) {
                    $completeValue = '<![CDATA[' . round($value, $decimals) . ']]>';
                } else {
                    $completeValue = '<![CDATA[' . $value . ']]>';
                }
            } else {
                if (is_numeric($value) === true) {
                    $completeValue = round($value, $decimals);
                } else {
                    $completeValue = $value;
                }
            }

            $this->openXMLElement($tag, false);
            $this->_writeString($completeValue, false, false);
            $this->closeXMLElement($tag, false);
        }
    }

}