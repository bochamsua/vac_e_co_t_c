<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Question edit form tab
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
class BS_Exam_Block_Adminhtml_Question_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Exam_Block_Adminhtml_Question_Edit_Tab_Form
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
            array('legend' => Mage::helper('bs_exam')->__('Question'))
        );

        $currentQuestion = Mage::registry('current_question');


        $curriculumId = null;
        if($this->getRequest()->getParam('curriculum_id')){
            $curriculumId = $this->getRequest()->getParam('curriculum_id');
        }elseif ($currentQuestion){
            $curriculumId = $currentQuestion->getCurriculumId();
        }

        Mage::register('question_curriculum_id', $curriculumId);


        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection');

        if($curriculumId){
            $values->addAttributeToFilter('entity_id', $curriculumId);
        }


        $values = $values->toOptionArray();
        $fieldset->addField(
            'curriculum_id',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Curriculum'),
                'name'      => 'curriculum_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => ''
            )
        );

        $subjectId = null;
        if($this->getRequest()->getParam('subject_id')){
            $subjectId = $this->getRequest()->getParam('subject_id');
        }elseif ($currentQuestion){
            $subjectId = $currentQuestion->getSubjectId();
        }

        Mage::register('question_subject_id', $subjectId);


        $values = Mage::getResourceModel('bs_subject/subject_collection');

        if($subjectId){
            $values->addFieldToFilter('entity_id', $subjectId);
        }


        $values = $values->toFullOptionArray();



        /*$html = '<a style="color: #eb5e00;" href="{#url}" id="question_subject_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeSubjectIdLink() {
                if ($(\'question_subject_id\').value == \'\') {
                    $(\'question_subject_id_link\').hide();
                } else {
                    $(\'question_subject_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/subject_subject/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View subject {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'question_subject_id\').value);
                    $(\'question_subject_id_link\').href = realUrl;
                    $(\'question_subject_id_link\').innerHTML = text.replace(\'{#name}\', $(\'question_subject_id\').options[$(\'question_subject_id\').selectedIndex].innerHTML);
                }
            }
            $(\'question_subject_id\').observe(\'change\', changeSubjectIdLink);
            changeSubjectIdLink();
            </script>';*/

        $fieldset->addField(
            'subject_id',
            'select',
            array(
                'label'     => Mage::helper('bs_exam')->__('Subject'),
                'name'      => 'subject_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
                'after_element_html' => ''
            )
        );


        $fieldset->addField(
            'question_question',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Question'),
                'name'  => 'question_question',
            'required'  => true,
            'class' => 'required-entry',

           )
        );



        $fieldset->addField(
            'question_level',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Level'),
                'name'  => 'question_level',

           )
        );

        $fieldset->addField(
            'question_order',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Sort Order'),
                'name'  => 'question_order',

           )
        );

        /*$fieldset->addField(
            'question_usage',
            'text',
            array(
                'label' => Mage::helper('bs_exam')->__('Usage'),
                'name'  => 'question_usage',

           )
        );*/
        /*$fieldset->addField(
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
        );*/
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
