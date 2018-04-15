<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Helper_Column_Renderer_Download extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $html = '';
        $url = Mage::helper('bs_worksheet/worksheet')->getFileBaseUrl() . $row->getWsFile();

        $html .= '<a href="'.$url.'" target="_blank">Download</a> ';

        return $html;
    }
}
