<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy edit form tab
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_InstructorCopy_Block_Adminhtml_Instructorcopy_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('instructorcopy_');
        $form->setFieldNameSuffix('instructorcopy');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'instructorcopy_form',
            array('legend' => Mage::helper('bs_instructorcopy')->__('Instructor Copy'))
        );

        $currentCopy = Mage::registry('current_instructorcopy');
        $cFromId = null;
        if($this->getRequest()->getParam('c_from')){
            $cFromId = $this->getRequest()->getParam('c_from');
        }elseif ($currentCopy){
            $cFromId = $currentCopy->getCFrom();
        }


        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection');
        if($cFromId){
            $values->addFieldToFilter('entity_id', $cFromId);
        }
        $values = $values->toOptionArray();



        $fieldset->addField(
            'c_from',
            'select',
            array(
                'label'     => Mage::helper('bs_instructorcopy')->__('Copy from Curriculum'),
                'name'      => 'c_from',
                'required'  => true,
                'values'    => $values,
                'after_element_html' => ''
            )
        );

        $cToId = null;
        if($this->getRequest()->getParam('c_to')){
            $cToId = $this->getRequest()->getParam('c_to');
        }elseif ($currentCopy){
            $cToId = $currentCopy->getCTo();
        }


        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection');
        if($cToId){
            $values->addFieldToFilter('entity_id', $cToId);
        }
        $values = $values->toOptionArray();



        $fieldset->addField(
            'c_to',
            'multiselect',
            array(
                'label'     => Mage::helper('bs_instructorcopy')->__('Copy to Curriculum'),
                'name'      => 'c_to',
                'required'  => true,
                'values'    => $values,
            )
        );


        $fieldset->addField(
            'clearall',
            'select',
            array(
                'label'  => Mage::helper('bs_instructorcopy')->__('Delete existing data?'),
                'name'   => 'clearall',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_instructorcopy')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_instructorcopy')->__('No'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_instructorcopy')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getInstructorcopyData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInstructorcopyData());
            Mage::getSingleton('adminhtml/session')->setInstructorcopyData(null);
        } elseif (Mage::registry('current_instructorcopy')) {
            $formValues = array_merge($formValues, Mage::registry('current_instructorcopy')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
