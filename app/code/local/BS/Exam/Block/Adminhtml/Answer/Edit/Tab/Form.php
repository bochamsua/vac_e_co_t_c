<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer edit form tab
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Answer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Answer_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('answer_');
        $form->setFieldNameSuffix('answer');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'answer_form',
            array('legend' => Mage::helper('bs_exam')->__('Answer'))
        );
        $values = Mage::getResourceModel('bs_exam/question_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="answer_question_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeQuestionIdLink() {
                if ($(\'answer_question_id\').value == \'\') {
                    $(\'answer_question_id_link\').hide();
                } else {
                    $(\'answer_question_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/exam_question/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'answer_question_id\').value);
                    $(\'answer_question_id_link\').href = realUrl;
                    $(\'answer_question_id_link\').innerHTML = text.replace(\'{#name}\', $(\'answer_question_id\').options[$(\'answer_question_id\').selectedIndex].innerHTML);
                }
            }
            $(\'answer_question_id\').observe(\'change\', changeQuestionIdLink);
            changeQuestionIdLink();
            </script>';

        $fieldset->addField(
            'question_id',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Question'),
                'name'      => 'question_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'answer_answer',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Answer'),
                'name'  => 'answer_answer',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'answer_correct',
            'select',
            array(
                'label' => Mage::helper('bs_exam')->__('Correct'),
                'name'  => 'answer_correct',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_exam')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_exam')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'answer_position',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Position'),
                'name'  => 'answer_position',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_exam')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_exam')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_exam')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_answer')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getAnswerData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAnswerData());
            Mage::getSingleton('adminhtml/session')->setAnswerData(null);
        } elseif (Mage::registry('current_answer')) {
            $formValues = array_merge($formValues, Mage::registry('current_answer')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
