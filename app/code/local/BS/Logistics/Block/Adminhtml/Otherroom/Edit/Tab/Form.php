<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Other room edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Otherroom_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Otherroom_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('otherroom_');
        $form->setFieldNameSuffix('otherroom');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'otherroom_form',
            array('legend' => Mage::helper('bs_logistics')->__('Other room'))
        );

        $fieldset->addField(
            'otherroom_name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'otherroom_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'otherroom_code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'otherroom_code',

           )
        );

        $fieldset->addField(
            'otherroom_location',
            'select',
            array(
                'label' => Mage::helper('bs_logistics')->__('Location'),
                'name'  => 'otherroom_location',

            'values'=> Mage::getModel('bs_logistics/otherroom_attribute_source_otherroomlocation')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'otherroom_area',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Area'),
                'name'  => 'otherroom_area',
            'note'	=> $this->__('Area in m&sup2;'),

           )
        );

        $fieldset->addField(
            'otherroom_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'otherroom_note',

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
        $formValues = Mage::registry('current_otherroom')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getOtherroomData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getOtherroomData());
            Mage::getSingleton('adminhtml/session')->setOtherroomData(null);
        } elseif (Mage::registry('current_otherroom')) {
            $formValues = array_merge($formValues, Mage::registry('current_otherroom')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
