<?php
/**
 * BS_Staff extension
 * 
 * @category       BS
 * @package        BS_Staff
 * @copyright      Copyright (c) 2015
 */
/**
 * Staff edit form tab
 *
 * @category    BS
 * @package     BS_Staff
 * @author Bui Phong
 */
class BS_Staff_Block_Adminhtml_Staff_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Staff_Block_Adminhtml_Staff_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('staff_');
        $form->setFieldNameSuffix('staff');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'staff_form',
            array('legend' => Mage::helper('bs_staff')->__('Staff'))
        );

        $fieldset->addField(
            'username',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('VAECO Username'),
                'name'  => 'username',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('VAECO ID'),
                'name'  => 'vaeco_id',

           )
        );

        $fieldset->addField(
            'fullname',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('Full Name'),
                'name'  => 'fullname',

           )
        );

        $fieldset->addField(
            'phone',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('Phone'),
                'name'  => 'phone',

           )
        );

        $fieldset->addField(
            'position',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('Position'),
                'name'  => 'position',

           )
        );

        $fieldset->addField(
            'division',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('Division'),
                'name'  => 'division',

           )
        );

        $fieldset->addField(
            'department',
            'text',
            array(
                'label' => Mage::helper('bs_staff')->__('Department'),
                'name'  => 'department',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_staff')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_staff')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_staff')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_staff')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getStaffData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getStaffData());
            Mage::getSingleton('adminhtml/session')->setStaffData(null);
        } elseif (Mage::registry('current_staff')) {
            $formValues = array_merge($formValues, Mage::registry('current_staff')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
