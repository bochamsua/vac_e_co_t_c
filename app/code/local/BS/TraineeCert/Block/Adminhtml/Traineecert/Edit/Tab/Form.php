<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Certificate edit form tab
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Block_Adminhtml_Traineecert_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_TraineeCert_Block_Adminhtml_Traineecert_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('traineecert_');
        $form->setFieldNameSuffix('traineecert');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'traineecert_form',
            array('legend' => Mage::helper('bs_traineecert')->__('Trainee Certificate'))
        );

        $courseId = $this->getRequest()->getParam('course_id');
        $traineeId = $this->getRequest()->getParam('trainee_id');

        $currentTraineeCert = Mage::registry('current_traineecert');


        if(!$courseId){
            $courseId = $currentTraineeCert->getCourseId();
        }

        if(!$traineeId){
            $traineeId = $currentTraineeCert->getTraineeId();
        }


        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku');
            //->addAttributeToFilter('status',1)
            //->addAttributeToFilter('course_report',0);

        if($courseId){
            $values->addAttributeToFilter('entity_id',$courseId);
        }


        $values = $values->toSkuOptionArray();

        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label' => Mage::helper('bs_traineecert')->__('Course'),
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,

           )
        );

        $values = Mage::getResourceModel('bs_trainee/trainee_collection')
            ->addAttributeToSelect('*');

        if($traineeId){
            $values->addAttributeToFilter('entity_id', $traineeId);
        }
        if($courseId){
            $values->addProductFilter($courseId);
        }

        $values = $values->toFullOptionArray();
        $fieldset->addField(
            'trainee_id',
            'select',
            array(
                'label' => Mage::helper('bs_traineecert')->__('Trainee'),
                'name'  => 'trainee_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,

           )
        );

        $fieldset->addField(
            'cert_no',
            'text',
            array(
                'label' => Mage::helper('bs_traineecert')->__('Certificate No'),
                'name'  => 'cert_no',

           )
        );

        $fieldset->addField(
            'issue_date',
            'date',
            array(
                'label' => Mage::helper('bs_traineecert')->__('Date'),
                'name'  => 'issue_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_traineecert')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_traineecert')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_traineecert')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_traineecert')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_traineecert')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTraineecertData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTraineecertData());
            Mage::getSingleton('adminhtml/session')->setTraineecertData(null);
        } elseif (Mage::registry('current_traineecert')) {
            $formValues = array_merge($formValues, Mage::registry('current_traineecert')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
