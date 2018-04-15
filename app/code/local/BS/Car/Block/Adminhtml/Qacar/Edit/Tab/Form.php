<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * QA Car edit form tab
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Qacar_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Qacar_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('qacar_');
        $form->setFieldNameSuffix('qacar');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'qacar_form',
            array('legend' => Mage::helper('bs_car')->__('QA Car'))
        );

        $fieldset->addField(
            'car_no',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('No'),
                'name'  => 'car_no',

           )
        );

        $fieldset->addField(
            'car_date',
            'date',
            array(
                'label' => Mage::helper('bs_car')->__('Date'),
                'name'  => 'car_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'sendto',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Send To'),
                'name'  => 'sendto',

           )
        );

        $fieldset->addField(
            'auditor',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Auditor'),
                'name'  => 'auditor',

           )
        );

        $fieldset->addField(
            'auditee',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Auditee representative'),
                'name'  => 'auditee',

           )
        );

        $fieldset->addField(
            'ref',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Audit Report Ref'),
                'name'  => 'ref',

           )
        );

        $fieldset->addField(
            'level',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Level'),
                'name'  => 'level',

           )
        );

        $fieldset->addField(
            'nc',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('NC Cause'),
                'name'  => 'nc',

           )
        );

        $fieldset->addField(
            'description',
            'textarea',
            array(
                'label' => Mage::helper('bs_car')->__('Non-Comformity Description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'expire_date',
            'date',
            array(
                'label' => Mage::helper('bs_car')->__('Correct Before Date'),
                'name'  => 'expire_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'root_cause',
            'textarea',
            array(
                'label' => Mage::helper('bs_car')->__('Identification of root cause'),
                'name'  => 'root_cause',

           )
        );

        $fieldset->addField(
            'corrective',
            'textarea',
            array(
                'label' => Mage::helper('bs_car')->__('Corrective Action'),
                'name'  => 'corrective',

           )
        );

        $fieldset->addField(
            'preventive',
            'textarea',
            array(
                'label' => Mage::helper('bs_car')->__('Preventive Action'),
                'name'  => 'preventive',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_car')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_car')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_car')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_qacar')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getQacarData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getQacarData());
            Mage::getSingleton('adminhtml/session')->setQacarData(null);
        } elseif (Mage::registry('current_qacar')) {
            $formValues = array_merge($formValues, Mage::registry('current_qacar')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
