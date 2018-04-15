<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Trainee_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Trainee_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('trainee_');
        $form->setFieldNameSuffix('trainee');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'trainee_form',
            array('legend' => Mage::helper('bs_docwise')->__('Trainee'))
        );

        $fieldset->addField(
            'trainee_name',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Trainee Name'),
                'name'  => 'trainee_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('VAECO ID'),
                'name'  => 'vaeco_id',

           )
        );

        $fieldset->addField(
            'dob',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Date of Birth'),
                'name'  => 'dob',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
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
        $formValues = Mage::registry('current_trainee')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTraineeData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTraineeData());
            Mage::getSingleton('adminhtml/session')->setTraineeData(null);
        } elseif (Mage::registry('current_trainee')) {
            $formValues = array_merge($formValues, Mage::registry('current_trainee')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
