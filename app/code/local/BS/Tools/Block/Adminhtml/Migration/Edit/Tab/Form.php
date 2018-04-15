<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Migration edit form tab
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Block_Adminhtml_Migration_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Tools_Block_Adminhtml_Migration_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('migration_');
        $form->setFieldNameSuffix('migration');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'migration_form',
            array('legend' => Mage::helper('bs_tools')->__('Migration'))
        );

        $fieldset->addField(
            'id_code',
            'textarea',
            array(
                'label' => Mage::helper('bs_tools')->__('Trainee Code & VAECO ID'),
                'name'  => 'id_code',
                'note'  => Mage::helper('bs_tools')->__('Put Trainee Code and VAECO ID in pair on each row. - or TAB is allowed. For example, HV547-VAE02907 or HV547   VAE02907'),

           )
        );


        $formValues = Mage::registry('current_migration')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getMigrationData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getMigrationData());
            Mage::getSingleton('adminhtml/session')->setMigrationData(null);
        } elseif (Mage::registry('current_migration')) {
            $formValues = array_merge($formValues, Mage::registry('current_migration')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
