<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document edit form tab
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('traineedoc_');
        $form->setFieldNameSuffix('traineedoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'traineedoc_form',
            array('legend' => Mage::helper('bs_traineedoc')->__('Trainee Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_traineedoc/adminhtml_traineedoc_helper_file')
        );

        $fieldset->addField(
            'course_id',
            'text',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Course'),
                'name'  => 'course_id',
                'readonly'  => true

            )
        );

        $fieldset->addField(
            'trainee_doc_name',
            'text',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Document Name'),
                'name'  => 'trainee_doc_name',

           )
        );

        $disabled = false;
        if(Mage::registry('current_traineedoc')->getId()){
            $disabled = true;
        }

        $fieldset->addField(
            'trainee_doc_type',
            'select',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Document Type'),
                'name'  => 'trainee_doc_type',

                'values'=> Mage::getModel('bs_traineedoc/traineedoc_attribute_source_traineedoctype')->getAllOptions(true),
                'disabled'  => $disabled
           )
        );

        $fieldset->addField(
            'trainee_doc_file',
            'file',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('File'),
                'name'  => 'trainee_doc_file',

           )
        );

        $fieldset->addField(
            'trainee_doc_date',
            'date',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Date'),
                'name'  => 'trainee_doc_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'value'              => date( Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                    strtotime('next day') )
            )
        );

        $fieldset->addField(
            'trainee_doc_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Note'),
                'name'  => 'trainee_doc_note',

           )
        );


        $traineeId = $this->getRequest()->getParam('trainee_id', false);
        $courseId = $this->getRequest()->getParam('product_id', false);
        $fieldset->addField(
            'hidden_trainee_id',
            'text',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Hidden Trainee Id'),
                'name'  => 'hidden_trainee_id',


            )
        );
        $formValues = Mage::registry('current_traineedoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTraineedocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTraineedocData());
            Mage::getSingleton('adminhtml/session')->setTraineedocData(null);
        } elseif (Mage::registry('current_traineedoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_traineedoc')->getData());
        }

        if($traineeId){
            $formValues = array_merge($formValues, array('hidden_trainee_id' => $traineeId, 'course_id'=>$courseId));
        }

        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
