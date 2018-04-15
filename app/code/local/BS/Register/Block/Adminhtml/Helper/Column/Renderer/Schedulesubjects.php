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
class BS_Register_Block_Adminhtml_Helper_Column_Renderer_Schedulesubjects extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
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
        /*if($row->getSubjectType()){
            $base = $row->getScheduleSubjects();
            $base = explode(",", $base);
        }else{
            $base = $row->getSubjectId();
            $base = array($base);
        } */
        $base = $row->getScheduleSubjects();
        $base = explode(",", $base);


        if (!$base) {
            return parent::render($row);
        }

        $result = array();

        foreach ($base as $item) {
            $subject = Mage::getModel('bs_subject/subject')->load($item);
            if($subject->getSubjectShortcode()){
                $result[] = $subject->getSubjectShortcode().' ('.$subject->getId().')';
            }else {
                $result[] = $subject->getSubjectName().' ('.$subject->getId().')';
            }

        }


        return implode(", ", $result);

    }
}
