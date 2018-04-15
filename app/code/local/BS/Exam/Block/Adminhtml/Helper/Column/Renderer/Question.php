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
class BS_Exam_Block_Adminhtml_Helper_Column_Renderer_Question extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
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
        $base = $row->getQuestionId();
        if (!$base) {
            return parent::render($row);
        }

        $subject = Mage::getModel('bs_exam/question')->load($base);

        return $subject->getQuestionQuestion();

    }
}
