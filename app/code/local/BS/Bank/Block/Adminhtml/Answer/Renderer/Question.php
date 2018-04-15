<?php
class BS_Bank_Block_Adminhtml_Answer_Renderer_Question extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row)
    {
        $question = Mage::getModel('bs_bank/question')->load($row->getQuestionId());
        return $question->getQuestionQuestion();
    }
}