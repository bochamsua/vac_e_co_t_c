<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * related entities column renderer
 * @category   BS
 * @package    BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Helper_Column_Renderer_Download extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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

        $doc = Mage::getModel('bs_docwise/docwisement')->load($row->getId());

        $file = $doc->getDocFile();

        $url = Mage::helper('bs_docwise/docwisement')->getFileBaseUrl() . $file;

        $name = str_replace(array("-","_")," ",$file);
        $name = ucwords($name);


        return '<a style="color: #eb5e00;" href="'.$url.'" target="_blank">'.$name.'</a>';
    }
}
