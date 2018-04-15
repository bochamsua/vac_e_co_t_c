<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Score (OLD) edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Scores_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Scores_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('scores_');
        $form->setFieldNameSuffix('scores');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'scores_form',
            array('legend' => Mage::helper('bs_docwise')->__('Score (OLD)'))
        );

        $fieldset->addField(
            'trainee_name',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Trainee'),
                'name'  => 'trainee_name',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Vaeco ID'),
                'name'  => 'vaeco_id',

           )
        );

        $fieldset->addField(
            'dob',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Dob'),
                'name'  => 'dob',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'score',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Score'),
                'name'  => 'score',

           )
        );

        $fieldset->addField(
            'cert_no',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Cert No'),
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
        $formValues = Mage::registry('current_scores')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getScoresData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getScoresData());
            Mage::getSingleton('adminhtml/session')->setScoresData(null);
        } elseif (Mage::registry('current_scores')) {
            $formValues = array_merge($formValues, Mage::registry('current_scores')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
