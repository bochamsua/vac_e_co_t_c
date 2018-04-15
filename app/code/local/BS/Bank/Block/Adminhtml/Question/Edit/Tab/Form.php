<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Question edit form tab
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Question_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Question_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('question_');
        $form->setFieldNameSuffix('question');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'question_form',
            array('legend' => Mage::helper('bs_bank')->__('Question'))
        );
        $values = Mage::getResourceModel('bs_bank/subject_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="question_subject_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeSubjectIdLink() {
                if ($(\'question_subject_id\').value == \'\') {
                    $(\'question_subject_id_link\').hide();
                } else {
                    $(\'question_subject_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/bank_subject/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'question_subject_id\').value);
                    $(\'question_subject_id_link\').href = realUrl;
                    $(\'question_subject_id_link\').innerHTML = text.replace(\'{#name}\', $(\'question_subject_id\').options[$(\'question_subject_id\').selectedIndex].innerHTML);
                }
            }
            $(\'question_subject_id\').observe(\'change\', changeSubjectIdLink);
            changeSubjectIdLink();
            </script>';

        $fieldset->addField(
            'subject_id',
            'select',
            array(
                'label'     => Mage::helper('bs_bank')->__('Subject'),
                'name'      => 'subject_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'question_question',
            'textarea',
            array(
                'label' => Mage::helper('bs_bank')->__('Question'),
                'name'  => 'question_question',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'curriculum_id',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Curriculum'),
                'name'  => 'curriculum_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'sort_order',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Sort Order'),
                'name'  => 'sort_order',

           )
        );

        $fieldset->addField(
            'question_usage',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Question Usage'),
                'name'  => 'question_usage',
            'note'	=> $this->__('times'),

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_bank')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_bank')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_bank')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_question')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getQuestionData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getQuestionData());
            Mage::getSingleton('adminhtml/session')->setQuestionData(null);
        } elseif (Mage::registry('current_question')) {
            $formValues = array_merge($formValues, Mage::registry('current_question')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
