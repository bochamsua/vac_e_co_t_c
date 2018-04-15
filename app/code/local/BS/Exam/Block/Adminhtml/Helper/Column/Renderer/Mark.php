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
class BS_Exam_Block_Adminhtml_Helper_Column_Renderer_Mark extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
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
        $mark = $base = $this->getColumn()->getMark();
        $courseId = Mage::registry('current_product')->getId();
        $traineeId = $row->getTraineeId();
        $subjectId = $row->getSubjectId();
        $examresult = Mage::getModel('bs_exam/examresult')
            ->getCollection()
            ->addFieldToFilter('course_id', $courseId)
            ->addFieldToFilter('trainee_id', $traineeId)
            ->addFieldToFilter('subject_id', $subjectId)
        ;
        $score = '';
        if($re = $examresult->getFirstItem()){
            switch ($mark){
                case 'first':
                    $score = $re->getFirstMark();
                    break;
                case 'second':
                    $score = $re->getSecondMark();
                    break;
                case 'third':
                    $score = $re->getThirdMark();
                    break;

            }
        }


        $html = '<input type="text" ';
        $html .= 'name="'.$mark.'[' . $courseId .','. $traineeId .','.$subjectId .']" ';
        $html .= 'value="' . $score . '"';
        $html .= 'class="input-text ' . $this->getColumn()->getInlineCss() . '"/>';


        return $html;

    }
}
