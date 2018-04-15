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
class BS_Register_Block_Adminhtml_Helper_Column_Renderer_Course extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
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
        $base = $row->getCourseId();
        if (!$base) {
            return parent::render($row);
        }

        $course = Mage::getModel('catalog/product')->load($base);

        return $course->getSku();

    }
}
