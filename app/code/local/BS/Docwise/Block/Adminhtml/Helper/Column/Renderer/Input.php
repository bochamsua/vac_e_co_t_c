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
class BS_Docwise_Block_Adminhtml_Helper_Column_Renderer_Input extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{

    public function render(Varien_Object $row)
    {

        $examId = Mage::registry('current_exam')->getId();

        $scoreModel = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('exam_id', $examId)->addFieldToFilter('trainee_id', $row->getTraineeId())->getFirstItem();
        $score = $scoreModel->getScore();

        $html = '<input type="text" ';
        $html .= 'name="score[' . $row->getTraineeId() . ']" ';
        $html .= 'value="' . $score . '"';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '"/>';

        //$html .= '<button onclick="updateField(this, '. $row->getVaecoId() .'); return false">' . Mage::helper('bs_subject')->__('Update') . '</button>';

        return $html;
    }
}
