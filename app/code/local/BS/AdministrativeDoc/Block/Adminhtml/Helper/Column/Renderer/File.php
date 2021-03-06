<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * parent entities column renderer
 * @category   BS
 * @package    BS_Logistics
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Block_Adminhtml_Helper_Column_Renderer_File extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    /**
     * render the column
     *
     * @access public
     * @param Varien_Object $row
     * @return string
     * @author Bui Phong
     */
    public function render(Varien_Object $row)
    {
        $base = $row->getId();
        if (!$base) {
            return parent::render($row);
        }

        $fileModel= Mage::getModel('bs_administrativedoc/administrativedocument')->load($base);
        $file = $fileModel->getDocFile();

        $url = Mage::helper('bs_administrativedoc/administrativedocument')->getFileBaseUrl().$file;
        $dir = Mage::helper('bs_administrativedoc/administrativedocument')->getFileBaseDir().$file;



        $filesize = Mage::helper('bs_coursedoc')->getFileSize($dir);
        $filesize = ' ['.$filesize.']';

        $html = '<a href="'.$url.'">Download</a> '.$filesize;

        return $html;

    }
}
