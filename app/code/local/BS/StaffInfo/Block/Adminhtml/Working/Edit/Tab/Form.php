<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Working edit form tab
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Working_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Working_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('working_');
        $form->setFieldNameSuffix('working');
        $this->setForm($form);

        $staffId = $this->getRequest()->getParam('staff_id');
        $currentTraining = Mage::registry('current_working');
        if($currentTraining->getStaffId()){
            $staffId = $currentTraining->getStaffId();
        }
        $staffName = '';
        if($staffId ){
            $staff = Mage::getModel('customer/customer')->load($staffId);
            $staffName = 'for '.$staff->getName();
        }

        $fieldset = $form->addFieldset(
            'working_form',
            array('legend' => Mage::helper('bs_staffinfo')->__('Related Working %s', $staffName))
        );

        $fieldset->addField(
            'staff_id',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Staff ID'),
                'name'  => 'staff_id',

           )
        );

        $fieldset->addField(
            'start_date',
            'date',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Start Date'),
                'name'  => 'start_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        $fieldset->addField(
            'end_date',
            'date',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('End Date'),
                'name'  => 'end_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        $fieldset->addField(
            'working_place',
            'textarea',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Working Place'),
                'name'  => 'working_place',

            )
        );
        $fieldset->addField(
            'working_as',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Working As'),
                'name'  => 'working_as',

            )
        );
        $fieldset->addField(
            'working_on',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Working On'),
                'name'  => 'working_on',

            )
        );

        $fieldset->addField(
            'remark',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Remark'),
                'name'  => 'remark',

           )
        );
        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('OR Import from this'),
                'name'  => 'import',

            )
        );
        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_staffinfo')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_staffinfo')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_staffinfo')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_working')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if($staffId){
            $formValues['staff_id'] = $staffId;
        }
        if (Mage::getSingleton('adminhtml/session')->getWorkingData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWorkingData());
            Mage::getSingleton('adminhtml/session')->setWorkingData(null);
        } elseif (Mage::registry('current_working')) {
            $formValues = array_merge($formValues, Mage::registry('current_working')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
