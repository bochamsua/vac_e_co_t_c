<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Info edit form tab
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Info_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Info_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('info_');
        $form->setFieldNameSuffix('info');
        $this->setForm($form);



        $instructorId = $this->getRequest()->getParam('instructor_id');
        $currentInfo = Mage::registry('current_info');
        if($currentInfo->getInstructorId()){
            $instructorId = $currentInfo->getInstructorId();
        }
        $name = '';
        if($instructorId ){
            $staff = Mage::getModel('bs_instructor/instructor')->load($instructorId);
            $name = 'of '.$staff->getIname();
        }


        $fieldset = $form->addFieldset(
            'info_form',
            array('legend' => Mage::helper('bs_instructorinfo')->__('Info %s', $name))
        );


        $fieldset->addField(
            'instructor_id',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Instructor'),
                'name'  => 'instructor_id',
                'readonly'  => true

           )
        );

        $fieldset->addField(
            'compliance_with',
            'select',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Compliance With'),
                'name'  => 'compliance_with',

            'values'=> Mage::getModel('bs_instructorinfo/info_attribute_source_compliancewith')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'approved_course',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Approved Course'),
                'name'  => 'approved_course',

           )
        );

        $fieldset->addField(
            'approved_function',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Approved Function'),
                'name'  => 'approved_function',

           )
        );

        $fieldset->addField(
            'approved_doc',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Approved Doc'),
                'name'  => 'approved_doc',

           )
        );

        $fieldset->addField(
            'approved_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Approved Date'),
                'name'  => 'approved_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'expire_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Expire Date'),
                'name'  => 'expire_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Note'),
                'name'  => 'note',

           )
        );

        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('OR Import from this?'),
                'name'  => 'import',

            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorinfo')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorinfo')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorinfo')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_info')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if($instructorId){
            $formValues['instructor_id'] = $instructorId;
        }
        if (Mage::getSingleton('adminhtml/session')->getInfoData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInfoData());
            Mage::getSingleton('adminhtml/session')->setInfoData(null);
        } elseif (Mage::registry('current_info')) {
            $formValues = array_merge($formValues, Mage::registry('current_info')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
