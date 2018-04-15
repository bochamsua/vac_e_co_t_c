<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Training edit form tab
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Block_Adminhtml_Training_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_StaffInfo_Block_Adminhtml_Training_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('training_');
        $form->setFieldNameSuffix('training');
        $this->setForm($form);

        $staffId = $this->getRequest()->getParam('staff_id');
        $currentTraining = Mage::registry('current_training');
        if($currentTraining->getStaffId()){
            $staffId = $currentTraining->getStaffId();
        }
        $staffName = '';
        if($staffId ){
           $staff = Mage::getModel('customer/customer')->load($staffId);
            $staffName = 'for '.$staff->getName();
        }

        $fieldset = $form->addFieldset(
            'training_form',
            array('legend' => Mage::helper('bs_staffinfo')->__('Related Training %s', $staffName))
        );

        $fieldset->addField(
            'staff_id',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Staff ID'),
                'name'  => 'staff_id',
                'readonly'  => true

           )
        );

        $fieldset->addField(
            'course',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Course'),
                'name'  => 'course',

           )
        );

        $fieldset->addField(
            'organization',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Country'),
                'name'  => 'organization',

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
            'certificate',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Proof of completion'),
                'name'  => 'certificate',

           )
        );

        $fieldset->addField(
            'keyword',
            'text',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Keyword'),
                'name'  => 'keyword',

           )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_staffinfo')->__('Note'),
                'name'  => 'note',

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

        $fieldset->addField(
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
        );
        $formValues = Mage::registry('current_training')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if($staffId){
            $formValues['staff_id'] = $staffId;
        }
        if (Mage::getSingleton('adminhtml/session')->getTrainingData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTrainingData());
            Mage::getSingleton('adminhtml/session')->setTrainingData(null);
        } elseif (Mage::registry('current_training')) {
            $formValues = array_merge($formValues, Mage::registry('current_training')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
