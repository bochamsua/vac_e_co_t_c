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
class BS_Tasktraining_Block_Adminhtml_Helper_Column_Renderer_Instructor extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        $base = $row->getInstructorId();
        if (!$base) {
            return parent::render($row);
        }

        $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->load($base);

        return $instructor->getName();

    }
}
