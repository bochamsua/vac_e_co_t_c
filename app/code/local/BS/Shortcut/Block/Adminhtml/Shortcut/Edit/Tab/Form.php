<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Shortcut edit form tab
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Block_Adminhtml_Shortcut_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Shortcut_Block_Adminhtml_Shortcut_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('shortcut_');
        $form->setFieldNameSuffix('shortcut');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'shortcut_form',
            array('legend' => Mage::helper('bs_shortcut')->__('Shortcut'))
        );

        $fieldset->addField(
            'shortcut',
            'text',
            array(
                'label' => Mage::helper('bs_shortcut')->__('Shortcut'),
                'name'  => 'shortcut',

           )
        );

        $fieldset->addField(
            'description',
            'textarea',
            array(
                'label' => Mage::helper('bs_shortcut')->__('Description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_shortcut')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_shortcut')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_shortcut')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_shortcut')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_shortcut')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getShortcutData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getShortcutData());
            Mage::getSingleton('adminhtml/session')->setShortcutData(null);
        } elseif (Mage::registry('current_shortcut')) {
            $formValues = array_merge($formValues, Mage::registry('current_shortcut')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
