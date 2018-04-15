<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * TC Task edit form tab
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Tctask_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Tctask_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('tctask_');
        $form->setFieldNameSuffix('tctask');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'tctask_form',
            array('legend' => Mage::helper('bs_report')->__('TC Task'))
        );



        $fieldset->addField(
            'tctask_name',
            'text',
            array(
                'label' => Mage::helper('bs_report')->__('Task Name'),
                'name'  => 'tctask_name',

           )
        );

        $fieldset->addField(
            'tctask_code',
            'text',
            array(
                'label' => Mage::helper('bs_report')->__('Task Code'),
                'name'  => 'tctask_code',

           )
        );

        $fieldset->addField(
            'supervisor_id',
            'select',
            array(
                'label' => Mage::helper('bs_report')->__('Supervisor'),
                'name'  => 'supervisor_id',
                'values'=> Mage::getModel('bs_report/report_attribute_source_supervisor')->getAllOptions(false),

           )
        );

        $fieldset->addField(
            'southern_supervisor_id',
            'select',
            array(
                'label' => Mage::helper('bs_report')->__('Southern Supervisor'),
                'name'  => 'southern_supervisor_id',
                'values'=> Mage::getModel('bs_report/report_attribute_source_southernsupervisor')->getAllOptions(false),

            )
        );
        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_report')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_report')->__('OR Import from this'),
                'name'  => 'import',

            )
        );
        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_report')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_report')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_report')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_tctask')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTctaskData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTctaskData());
            Mage::getSingleton('adminhtml/session')->setTctaskData(null);
        } elseif (Mage::registry('current_tctask')) {
            $formValues = array_merge($formValues, Mage::registry('current_tctask')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
