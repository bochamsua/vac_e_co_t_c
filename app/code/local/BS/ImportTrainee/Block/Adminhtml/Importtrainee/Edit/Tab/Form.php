<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee edit form tab
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
class BS_ImportTrainee_Block_Adminhtml_Importtrainee_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_ImportTrainee_Block_Adminhtml_Importtrainee_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('importtrainee_');
        $form->setFieldNameSuffix('importtrainee');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'importtrainee_form',
            array('legend' => Mage::helper('bs_importtrainee')->__('Import Trainee'))
        );

        $currentimport = Mage::registry('current_importtrainee');
        $productId = null;
        if($this->getRequest()->getParam('product_id')){
            $productId = $this->getRequest()->getParam('product_id');
        }elseif ($currentimport){
            $productId = $currentimport->getCourseId();
        }

        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku');

        if($productId){
            $values->addAttributeToFilter('entity_id',$productId);
        }


        $values = $values->toSkuOptionArray();


        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label'     => Mage::helper('bs_importtrainee')->__('Course'),
                'name'      => 'course_id',
                'values'    => $values,
                'after_element_html' => ''
            )
        );


        $fieldset->addField(
            'vaeco_ids',
            'textarea',
            array(
                'label' => Mage::helper('bs_importtrainee')->__('VAECO IDs'),
                'name'  => 'vaeco_ids',
            'note'	=> $this->__('Enter VAECO ID one by one on each row. VAECO ID can be input in full format like "VAE02907" or just "02907" or "2907"'),

           )
        );
        $fieldset->addField(
            'trainee_ids',
            'textarea',
            array(
                'label' => Mage::helper('bs_importtrainee')->__('Trainee IDs'),
                'name'  => 'trainee_ids',
                'note'	=> $this->__('Enter Trainee ID one by one on each row. For example: HV888'),

            )
        );
        $fieldset->addField(
            'clearall',
            'select',
            array(
                'label'  => Mage::helper('bs_importtrainee')->__('Clear all current trainees?'),
                'name'   => 'clearall',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_importtrainee')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_importtrainee')->__('No'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_importtrainee')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getImporttraineeData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getImporttraineeData());
            Mage::getSingleton('adminhtml/session')->setImporttraineeData(null);
        } elseif (Mage::registry('current_importtrainee')) {
            $formValues = array_merge($formValues, Mage::registry('current_importtrainee')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
