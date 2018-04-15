<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Other Info edit form tab
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Otherinfo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_InstructorInfo_Block_Adminhtml_Otherinfo_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('otherinfo_');
        $form->setFieldNameSuffix('otherinfo');
        $this->setForm($form);

        $instructorId = $this->getRequest()->getParam('instructor_id');
        $currentInfo = Mage::registry('current_otherinfo');
        if($currentInfo->getInstructorId()){
            $instructorId = $currentInfo->getInstructorId();
        }
        $name = '';
        if($instructorId ){
            $staff = Mage::getModel('bs_instructor/instructor')->load($instructorId);
            $name = 'of '.$staff->getIname();
        }

        $fieldset = $form->addFieldset(
            'otherinfo_form',
            array('legend' => Mage::helper('bs_instructorinfo')->__('Training Info %s', $name))
        );

        $fieldset->addField(
            'instructor_id',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Instructor'),
                'name'  => 'instructor_id',

            )
        );

        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Training Description'),
                'name'  => 'title',

           )
        );



        $fieldset->addField(
            'country',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Country'),
                'name'  => 'country',

           )
        );

        $fieldset->addField(
            'start_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Start Date'),
                'name'  => 'start_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'end_date',
            'date',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('End Date'),
                'name'  => 'end_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'cert_info',
            'text',
            array(
                'label' => Mage::helper('bs_instructorinfo')->__('Cert.#/Evidence'),
                'name'  => 'cert_info',

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
        $formValues = Mage::registry('current_otherinfo')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if($instructorId){
            $formValues['instructor_id'] = $instructorId;
        }

        if (Mage::getSingleton('adminhtml/session')->getOtherinfoData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getOtherinfoData());
            Mage::getSingleton('adminhtml/session')->setOtherinfoData(null);
        } elseif (Mage::registry('current_otherinfo')) {
            $formValues = array_merge($formValues, Mage::registry('current_otherinfo')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
