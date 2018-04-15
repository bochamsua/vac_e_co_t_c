<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire edit form tab
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Block_Adminhtml_Questionnaire_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Questionnaire_Block_Adminhtml_Questionnaire_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('questionnaire_');
        $form->setFieldNameSuffix('questionnaire');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'questionnaire_form',
            array('legend' => Mage::helper('bs_questionnaire')->__('Questionnaire'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_questionnaire/adminhtml_questionnaire_helper_file')
        );


        $courseId = $this->getRequest()->getParam('course_id', false);
        $subs = array();
        if($courseId){
            //get training curriculum
            $cur = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($courseId)->getFirstItem();
            if($cur->getId()){
                $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('status', 1)->addFieldToFilter('require_exam', 1)->addFieldToFilter('curriculum_id',$cur->getId());
                if($subjects->count()){
                    $subs = $subjects->toOptionHash();
                }

            }
        }


        $fieldset->addField(
            'course_id',
            'text',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Course'),
                'name'  => 'course_id',
                'readonly'  => true

            )
        );

        $fieldset->addField(
            'subject_id',
            'select',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Subject'),
                'name'  => 'subject_id',
                'note'	=> $this->__('Please select the Subject you want to submit the questionnaire'),
                'values'    => $subs

           )
        );


        $fieldset->addField(
            'question_time',
            'text',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Time per question'),
                'name'  => 'question_time',
                'class' => 'validate-number',

                'note'	=> $this->__('How many minutes per question. Default 1.5'),

            )
        );

        $values = array();
        for($i=1; $i<100; $i++){
            $values[$i] = $i;
        }

        $fieldset->addField(
            'number_of_times',
            'select',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Number of Questionnaires'),
                'name'  => 'number_of_times',
                'values'	=> $values,
                'note'	=> $this->__('Normally 2: Odd and Even'),

           )
        );

        $fieldset->addField(
            'exam_date',
            'date',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Exam Date'),
                'name'  => 'exam_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'value'              => date( Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                    strtotime('next day') )
            )
        );



        $fieldset->addField(
            'questions',
            'textarea',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Questions'),
                'name'  => 'questions',

           )
        );

        $fieldset->addField(
            'subject_size',
            'text',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Subject font size'),
                'name'  => 'subject_size',
                'class' => 'validate-number',

                'note'	=> $this->__('Default is 12 px. Put number only. For example: 12'),

            )
        );
        $fieldset->addField(
            'question_size',
            'text',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Question/Answer font size'),
                'name'  => 'question_size',
                'class' => 'validate-number',
                'note'	=> $this->__('Default is 10 px. Put number only. For example: 10'),

            )
        );

        $fieldset->addField(
            'spacing',
            'text',
            array(
                'label' => Mage::helper('bs_questionnaire')->__('Lines spacing'),
                'name'  => 'spacing',
                'note'	=> $this->__('For question and answer lines spacing. Format: x-y (means: before  x px, after x px). For example: 1-1. Default: 0-0'),

            )
        );

        $fieldset->addField(
            'highlight',
            'select',
            array(
                'label'  => Mage::helper('bs_questionnaire')->__('Highlight correct answer?'),
                'name'   => 'highlight',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_questionnaire')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_questionnaire')->__('No'),
                    ),
                ),
                'note'  => Mage::helper('bs_questionnaire')->__('If yes, correct answers will be highlighted in output questionnaires')
            )
        );

        $fieldset->addField(
            'compress',
            'select',
            array(
                'label'  => Mage::helper('bs_questionnaire')->__('Compress?'),
                'name'   => 'compress',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_questionnaire')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_questionnaire')->__('No'),
                    ),
                ),
            )
        );

        $formValues = Mage::registry('current_questionnaire')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getQuestionnaireData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getQuestionnaireData());
            Mage::getSingleton('adminhtml/session')->setQuestionnaireData(null);
        } elseif (Mage::registry('current_questionnaire')) {
            $formValues = array_merge($formValues, Mage::registry('current_questionnaire')->getData());
        }

        $formValues = array_merge($formValues, array('course_id'=>$courseId));

        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('questionnaire_course_id') != undefined){
                        $('questionnaire_course_id').up('tr').setStyle({'display':'none'});
                    }





                </script>";

        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
