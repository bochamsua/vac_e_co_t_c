<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Instructor edit form tab
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Block_Adminhtml_Importinstructor_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_ImportInstructor_Block_Adminhtml_Importinstructor_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('importinstructor_');
        $form->setFieldNameSuffix('importinstructor');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'importinstructor_form',
            array('legend' => Mage::helper('bs_importinstructor')->__('Import Instructor'))
        );

        $currentimport = Mage::registry('current_importinstructor');
        $curriculumId = null;
        if($this->getRequest()->getParam('curriculum_id')){
            $curriculumId = $this->getRequest()->getParam('curriculum_id');
        }elseif ($currentimport){
            $curriculumId = $currentimport->getCurriculumId();
        }

        $values = Mage::getModel('bs_traininglist/curriculum')->getCollection()
            ->addAttributeToSelect('iname');

        if($curriculumId){
            $values->addAttributeToFilter('entity_id',$curriculumId);
        }


        $values = $values->toOptionArray();


        $fieldset->addField(
            'curriculum_id',
            'select',
            array(
                'label' => Mage::helper('bs_importinstructor')->__('Curriculum'),
                'name'  => 'curriculum_id',
                'values'    => $values,

           )
        );

        $fieldset->addField(
            'vaeco_ids',
            'textarea',
            array(
                'label' => Mage::helper('bs_importinstructor')->__('VAECO IDs'),
                'name'  => 'vaeco_ids',
            'note'	=> $this->__('Enter VAECO ID one by one on each row'),

           )
        );
        $fieldset->addField(
            'clearall',
            'select',
            array(
                'label'  => Mage::helper('bs_importinstructor')->__('Clear all current Instructors?'),
                'name'   => 'clearall',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_importinstructor')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_importinstructor')->__('No'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_importinstructor')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getImportinstructorData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getImportinstructorData());
            Mage::getSingleton('adminhtml/session')->setImportinstructorData(null);
        } elseif (Mage::registry('current_importinstructor')) {
            $formValues = array_merge($formValues, Mage::registry('current_importinstructor')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
