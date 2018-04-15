<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Score edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Score_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Score_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('score_');
        $form->setFieldNameSuffix('score');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'score_form',
            array('legend' => Mage::helper('bs_docwise')->__('Score'))
        );

        $currentScore = Mage::registry('current_score');

        $traineeId = null;
        if($this->getRequest()->getParam('trainee_id')){
            $traineeId = $this->getRequest()->getParam('trainee_id');
        }elseif ($currentScore){
            $traineeId = $currentScore->getTraineeId();
        }

        $examId = null;
        if($this->getRequest()->getParam('exam_id')){
            $examId = $this->getRequest()->getParam('exam_id');
        }elseif ($currentScore){
            $examId = $currentScore->getExamId();
        }



        $fieldset->addField(
            'exam_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exam'),
                'name'  => 'exam_id',

           )
        );

        $fieldset->addField(
            'trainee_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Trainee'),
                'name'  => 'trainee_id',
                'note'  => 'You can put VAECO ID into this field and press ENTER then the trainee ID will be updated. VAECO ID can be in any of following formats: VAE02907, 02907 or just 2907.'

           )
        );

        $fieldset->addField(
            'score',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Score'),
                'name'  => 'score',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'cert_no',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Certificate No'),
                'name'  => 'cert_no',

           )
        );

        $fieldset->addField(
            'exam_date',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exam Date'),
                'name'  => 'exam_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'expire_date',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Expire Date'),
                'name'  => 'expire_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'question_no',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Question No'),
                'name'  => 'question_no',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_docwise')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_docwise')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_docwise')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_score')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getScoreData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getScoreData());
            Mage::getSingleton('adminhtml/session')->setScoreData(null);
        } elseif (Mage::registry('current_score')) {
            $formValues = array_merge($formValues, Mage::registry('current_score')->getData());
        }

        if($traineeId){
            $formValues = array_merge($formValues, array('trainee_id' => $traineeId));
        }

        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
