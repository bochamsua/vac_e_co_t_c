<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Form Template edit form tab
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Block_Adminhtml_Formtemplate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Formtemplate_Block_Adminhtml_Formtemplate_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('formtemplate_');
        $form->setFieldNameSuffix('formtemplate');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'formtemplate_form',
            array('legend' => Mage::helper('bs_formtemplate')->__('Form Template'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_formtemplate/adminhtml_formtemplate_helper_file')
        );

        $fieldset->addField(
            'template_name',
            'text',
            array(
                'label' => Mage::helper('bs_formtemplate')->__('Name'),
                'name'  => 'template_name',

           )
        );

        $fieldset->addField(
            'template_code',
            'text',
            array(
                'label' => Mage::helper('bs_formtemplate')->__('Code'),
                'name'  => 'template_code',

           )
        );

        $fieldset->addField(
            'template_file',
            'file',
            array(
                'label' => Mage::helper('bs_formtemplate')->__('File'),
                'name'  => 'template_file',

           )
        );

        $fieldset->addField(
            'template_date',
            'date',
            array(
                'label' => Mage::helper('bs_formtemplate')->__('Approved Date'),
                'name'  => 'template_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'template_revision',
            'select',
            array(
                'label' => Mage::helper('bs_formtemplate')->__('Revision'),
                'name'  => 'template_revision',

            'values'=> Mage::getModel('bs_formtemplate/formtemplate_attribute_source_templaterevision')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'template_note',
            'text',
            array(
                'label' => Mage::helper('bs_formtemplate')->__('Note'),
                'name'  => 'template_note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_formtemplate')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_formtemplate')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_formtemplate')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_formtemplate')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getFormtemplateData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFormtemplateData());
            Mage::getSingleton('adminhtml/session')->setFormtemplateData(null);
        } elseif (Mage::registry('current_formtemplate')) {
            $formValues = array_merge($formValues, Mage::registry('current_formtemplate')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
