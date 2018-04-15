<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Cost Group edit form tab
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Costgroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Costgroup_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('costgroup_');
        $form->setFieldNameSuffix('costgroup');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'costgroup_form',
            array('legend' => Mage::helper('bs_coursecost')->__('Cost Group'))
        );

        $fieldset->addField(
            'group_name',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Group Name'),
                'name'  => 'group_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'group_code',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Code'),
                'name'  => 'group_code',

           )
        );
        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_coursecost')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_coursecost')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_coursecost')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_costgroup')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCostgroupData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCostgroupData());
            Mage::getSingleton('adminhtml/session')->setCostgroupData(null);
        } elseif (Mage::registry('current_costgroup')) {
            $formValues = array_merge($formValues, Mage::registry('current_costgroup')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
