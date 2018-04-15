<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Get Info edit form tab
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Block_Adminhtml_Getinfo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Getinfo_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('getinfo_');
        $form->setFieldNameSuffix('getinfo');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'getinfo_form',
            array('legend' => Mage::helper('bs_tools')->__('Get Info'))
        );

        $fieldset->addField(
            'vaeco_ids',
            'textarea',
            array(
                'label' => Mage::helper('bs_tools')->__('VAECO IDs'),
                'name'  => 'vaeco_ids',
                'class' => 'required-entry',
                'required'  => true

           )
        );

        $fieldset->addField(
            'option',
            'text',
            array(
                'label' => Mage::helper('bs_tools')->__('Option'),
                'name'  => 'option',

            )
        );

        $fieldset->addField(
            'action_type',
            'select',
            array(
                'label' => Mage::helper('bs_tools')->__('Action'),
                'name'  => 'action_type',

            'values'=> Mage::getModel('bs_tools/getinfo_attribute_source_actiontype')->getAllOptions(true),
           )
        );

        $formValues = Mage::registry('current_getinfo')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getGetinfoData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getGetinfoData());
            Mage::getSingleton('adminhtml/session')->setGetinfoData(null);
        } elseif (Mage::registry('current_getinfo')) {
            $formValues = array_merge($formValues, Mage::registry('current_getinfo')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
