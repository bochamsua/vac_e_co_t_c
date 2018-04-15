<?php
/**
 * BS_CourseDoc extension
 *
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */

/**
 * CourseDoc default helper
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])
            ) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getFileSize($file)
    {
        $bytes = filesize($file);
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0';
        }

        return $bytes;
    }

    public function countPdfPages($file)
    {
        $cmd = Mage::getBaseDir('media') . DS . 'pdftools/pdfinfo';

        $info = new SplFileInfo($file);
        $extension = $info->getExtension();

        if (strtolower($extension) == 'pdf') {
            // Parse entire output
            // Surround with double quotes if file name has spaces
            //pdfinfo $PDF_File | grep Pages | awk '{print $2}'
            exec("$cmd \"$file\" | grep Pages", $output);

            $page = preg_replace("/[^0-9]/", "", $output[0]);
            $pagecount = intval($page);

            return $pagecount;
        }elseif($extension == 'docx'){
            return $this->countOfficePages($file);
        }elseif($extension == 'pptx'){
            return $this->countOfficePages($file, 'pptx');
        }

        return false;


    }


    function countOfficePages($file, $type = 'docx')
    {
        $pageCount = false;

        $zip = new ZipArchive();

        if ($zip->open($file) === true) {
            if (($index = $zip->locateName('docProps/app.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                $xml = new SimpleXMLElement($data);
                if($type == 'docx'){
                    $pageCount = $xml->Pages;
                }elseif($type == 'pptx'){
                    $pageCount = $xml->Slides;
                }

            }
            $zip->close();
        }

        return $pageCount;
    }

    public function getRarFiles($rar){
        $cmd = Mage::getBaseDir('media') . DS . 'rartools/unrar';

        $info = new SplFileInfo($rar);
        $extension = $info->getExtension();

        if (strtolower($extension) == 'rar') {

            exec("$cmd l \"$rar\" | grep \"\.A\.\"", $output);

            if(count($output)){
                $result = '<br>Rar/Zip content: <br>';
                foreach ($output as $item) {
                    $item = trim($item);
                    $item = explode(" ", $item);

                    //find the correct data for size
                    $sizeIndex = false;
                    $nameIndex = false;
                    for($i=0; $i < count($item); $i++){
                        $check = intval($item[$i]);
                        if($check > 1){
                            $sizeIndex = $i; break;
                        }
                    }
                    for($i=0; $i < count($item); $i++){
                        if(strpos($item[$i], ":")){//found hour:minute
                            $nameIndex = $i+1; break;
                        }
                    }
                    $size = '';
                    if($sizeIndex){
                        $size = $this->getHFileSize($item[$sizeIndex]);
                    }

                    $name = '';
                    for($i=$nameIndex; $i < count($item); $i++){
                        $name .= $item[$i].' ';
                    }
                    $name = trim($name);

                    $result .= $name.' ['.$size.'] <br>';

                }
            }


            return $result;
        }

        return false;

    }

    public function getHFileSize($bytes)
    {

        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0';
        }

        return $bytes;
    }
}
