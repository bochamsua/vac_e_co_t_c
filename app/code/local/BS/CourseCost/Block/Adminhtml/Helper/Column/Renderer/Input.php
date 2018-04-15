<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * parent entities column renderer
 * @category   BS
 * @package    BS_Docwise
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Helper_Column_Renderer_Input extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{

    public function render(Varien_Object $row)
    {

        $scheduleId = $row->getId();
        //$subject = Mage::getModel('bs_subject/subject')->load($subjectId);
        $order = $row->getCoursecostOrder();

        $html = '<input type="text" ';
        $html .= 'name="coursecostposition[' . $scheduleId . ']" ';
        $html .= 'value="' . $order . '"';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '"/>';


        return $html;
    }
}
