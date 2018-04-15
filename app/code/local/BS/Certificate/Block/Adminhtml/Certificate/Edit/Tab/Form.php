<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * Certificate edit form tab
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Block_Adminhtml_Certificate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Certificate_Block_Adminhtml_Certificate_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('certificate_');
        $form->setFieldNameSuffix('certificate');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'certificate_form',
            array('legend' => Mage::helper('bs_certificate')->__('Certificate'))
        );

        $fieldset->addField(
            'staff_name',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Staff Name'),
                'name'  => 'staff_name',

           )
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('VAECO ID'),
                'name'  => 'vaeco_id',

           )
        );

        $fieldset->addField(
            'cert_type',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Cert Type'),
                'name'  => 'cert_type',

            )
        );

        $fieldset->addField(
            'description',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Description'),
                'name'  => 'description',

           )
        );



        $fieldset->addField(
            'apply_for',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Apply For'),
                'name'  => 'apply_for',

           )
        );

        $fieldset->addField(
            'cert_no',
            'text',
            array(
                'label' => Mage::helper('bs_certificate')->__('Cert Number'),
                'name'  => 'cert_no',

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
        $formValues = Mage::registry('current_certificate')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCertificateData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCertificateData());
            Mage::getSingleton('adminhtml/session')->setCertificateData(null);
        } elseif (Mage::registry('current_certificate')) {
            $formValues = array_merge($formValues, Mage::registry('current_certificate')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
