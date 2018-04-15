<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Workshop_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Workshop_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('workshop_');
        $form->setFieldNameSuffix('workshop');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'workshop_form',
            array('legend' => Mage::helper('bs_logistics')->__('Workshop'))
        );

        $fieldset->addField(
            'workshop_name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'workshop_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'name_vi',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Vietnamese Name'),
                'name'  => 'name_vi',


            )
        );

        $fieldset->addField(
            'workshop_code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'workshop_code',

           )
        );

        $fieldset->addField(
            'workshop_area',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Area'),
                'name'  => 'workshop_area',
            'note'	=> $this->__('Area in m&sup2;'),

           )
        );

        $fieldset->addField(
            'workshop_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'workshop_note',

           )
        );

        $fieldset->addField(
            'workshop_location',
            'select',
            array(
                'label' => Mage::helper('bs_logistics')->__('Location'),
                'name'  => 'workshop_location',

            'values'=> Mage::getModel('bs_logistics/workshop_attribute_source_workshoplocation')->getAllOptions(true),
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
        $formValues = Mage::registry('current_workshop')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWorkshopData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWorkshopData());
            Mage::getSingleton('adminhtml/session')->setWorkshopData(null);
        } elseif (Mage::registry('current_workshop')) {
            $formValues = array_merge($formValues, Mage::registry('current_workshop')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
