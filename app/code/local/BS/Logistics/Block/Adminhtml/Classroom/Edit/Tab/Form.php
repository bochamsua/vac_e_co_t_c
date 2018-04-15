<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('classroom_');
        $form->setFieldNameSuffix('classroom');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'classroom_form',
            array('legend' => Mage::helper('bs_logistics')->__('Classroom/Examroom'))
        );

        $fieldset->addField(
            'classroom_name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'classroom_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'classroom_code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'classroom_code',

           )
        );

        $fieldset->addField(
            'classroom_location',
            'select',
            array(
                'label' => Mage::helper('bs_logistics')->__('Location'),
                'name'  => 'classroom_location',

            'values'=> Mage::getModel('bs_logistics/classroom_attribute_source_classroomlocation')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'classroom_area',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Area'),
                'name'  => 'classroom_area',
            'note'	=> $this->__('Area in m&sup2;'),

           )
        );

        $fieldset->addField(
            'classroom_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'classroom_note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_logistics')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_logistics')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_logistics')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_classroom')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getClassroomData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getClassroomData());
            Mage::getSingleton('adminhtml/session')->setClassroomData(null);
        } elseif (Mage::registry('current_classroom')) {
            $formValues = array_merge($formValues, Mage::registry('current_classroom')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
