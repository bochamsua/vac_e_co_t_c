<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * CRS edit form tab
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Block_Adminhtml_Crs_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Crs_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('crs_');
        $form->setFieldNameSuffix('crs');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'crs_form',
            array('legend' => Mage::helper('bs_certificate')->__('CRS'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Name'),
                'name'  => 'name',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Staff ID'),
                'name'  => 'vaeco_id',

           )
        );

        $fieldset->addField(
            'authorization_number',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Authorization Number'),
                'name'  => 'authorization_number',

           )
        );

        $fieldset->addField(
            'category',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Category'),
                'name'  => 'category',

           )
        );

        $fieldset->addField(
            'ac_type',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('A/C Type'),
                'name'  => 'ac_type',

           )
        );

        $fieldset->addField(
            'engine_type',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Engine Type'),
                'name'  => 'engine_type',

           )
        );

        $fieldset->addField(
            'issue_date',
            'date',
            array(
                'label' => Mage::helper('bs_certificate')->__('Issue Date'),
                'name'  => 'issue_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'expire_date',
            'date',
            array(
                'label' => Mage::helper('bs_certificate')->__('Expire Date'),
                'name'  => 'expire_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'function_title',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Function'),
                'name'  => 'function_title',

           )
        );

        $fieldset->addField(
            'limitation',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Limitation'),
                'name'  => 'limitation',

           )
        );

        $fieldset->addField(
            'reason',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Reason'),
                'name'  => 'reason',

           )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_certificate')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_certificate')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_certificate')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_certificate')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_crs')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCrsData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCrsData());
            Mage::getSingleton('adminhtml/session')->setCrsData(null);
        } elseif (Mage::registry('current_crs')) {
            $formValues = array_merge($formValues, Mage::registry('current_crs')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
