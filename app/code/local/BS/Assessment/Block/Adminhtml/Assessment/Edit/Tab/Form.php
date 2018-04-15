<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment edit form tab
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Block_Adminhtml_Assessment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Assessment_Block_Adminhtml_Assessment_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('assessment_');
        $form->setFieldNameSuffix('assessment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'assessment_form',
            array('legend' => Mage::helper('bs_assessment')->__('Assessment'))
        );

        $currentExam = Mage::registry('current_assessment');
        if($this->getRequest()->getParam('product_id')){
            $productId = $this->getRequest()->getParam('product_id');
        }elseif ($currentExam){
            $productId = $currentExam->getCourseId();
        }
        if($productId){
            $values = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('sku')
                ->addAttributeToFilter('entity_id',$productId)
            ;
            $values = $values->toSkuOptionArray();


            $fieldset->addField(
                'course_id',
                'select',
                array(
                    'label'     => Mage::helper('bs_assessment')->__('Course'),
                    'name'      => 'course_id',
                    'required'  => true,
                    'class' => 'required-entry',
                    'values'    => $values,
                )
            );

            $fieldset->addField(
                'content',
                'text',
                array(
                    'label' => Mage::helper('bs_assessment')->__('Content'),
                    'name'  => 'content',
                    'required'  => true,
                    'class' => 'required-entry',

                )
            );
            $fieldset->addField(
                'suffix',
                'text',
                array(
                    'label' => Mage::helper('bs_assessment')->__('Code Suffix'),
                    'name'  => 'suffix',
                    'note'	=> $this->__('This is a shortcode of content. For example, "MEL & CDL Items" can be "MEL"; "Block 1 & Block 4" can be "B14"'),

                )
            );

            $fieldset->addField(
                'detail',
                'textarea',
                array(
                    'label' => Mage::helper('bs_assessment')->__('Detail'),
                    'name'  => 'detail',
                    'note'	=> $this->__('Format: Date (dd/mm) -- TraineeVAECOID (VAE02907, 02907 or just 2907) -- Instructor VAECOID (VAE02907, 02907 or just 2907) -- time (hours). Example: <strong>31/03 -- 2907 -- 1869 </strong>. If time is empty, total time will be used.'),

                )
            );

            //We check in CAAV Certificate List
            /*$fieldset->addField(
                'caav_note',
                'textarea',
                array(
                    'label' => Mage::helper('bs_assessment')->__('CAAV Note'),
                    'name'  => 'caav_note',
                    'note'	=> $this->__('Format: TraineeVAECOID (VAE02907, 02907 or just 2907) -- Cert_No -- Issue Date (dd/mm/yyyy) -- Expire Date (dd/mm/yyyy). Example: <strong>2907 -- CAAV/85 -- 31/03/2015 -- 31/03/2018</strong>. If empty, means all trainees don\'t have CAAV licenses.'),

                )
            );*/

            $fieldset->addField(
                'duration',
                'text',
                array(
                    'label' => Mage::helper('bs_assessment')->__('Duration'),
                    'name'  => 'duration',
                    'note'	=> $this->__('How long the assessment will take?'),

                )
            );

            $fieldset->addField(
                'article',
                'select',
                array(
                    'label' => Mage::helper('bs_assessment')->__('On article'),
                    'name'  => 'article',
                    'required'  => true,
                    'class' => 'required-entry',

                    'values'=> Mage::getModel('bs_assessment/assessment_attribute_source_article')->getAllOptions(true),
                )
            );

            $fieldset->addField(
                'app_type',
                'select',
                array(
                    'label' => Mage::helper('bs_assessment')->__('App Type'),
                    'name'  => 'app_type',
                    'required'  => true,
                    'class' => 'required-entry',

                    'values'=> Mage::getModel('bs_assessment/assessment_attribute_source_apptype')->getAllOptions(true),
                )
            );

            $fieldset->addField(
                'app_cat',
                'select',
                array(
                    'label' => Mage::helper('bs_assessment')->__('App Cat'),
                    'name'  => 'app_cat',

                    'values'=> Mage::getModel('bs_assessment/assessment_attribute_source_appcat')->getAllOptions(true),
                )
            );

            $fieldset->addField(
                'prepared_date',
                'date',
                array(
                    'label' => Mage::helper('bs_assessment')->__('Prepared Date'),
                    'name'  => 'prepared_date',

                    'image' => $this->getSkinUrl('images/grid-cal.gif'),
                    'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                )
            );
            $fieldset->addField(
                'status',
                'select',
                array(
                    'label'  => Mage::helper('bs_assessment')->__('Status'),
                    'name'   => 'status',
                    'values' => array(
                        array(
                            'value' => 1,
                            'label' => Mage::helper('bs_assessment')->__('Enabled'),
                        ),
                        array(
                            'value' => 0,
                            'label' => Mage::helper('bs_assessment')->__('Disabled'),
                        ),
                    ),
                )
            );
            $formValues = Mage::registry('current_assessment')->getDefaultValues();
            if (!is_array($formValues)) {
                $formValues = array();
            }
            if (Mage::getSingleton('adminhtml/session')->getAssessmentData()) {
                $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAssessmentData());
                Mage::getSingleton('adminhtml/session')->setAssessmentData(null);
            } elseif (Mage::registry('current_assessment')) {
                $formValues = array_merge($formValues, Mage::registry('current_assessment')->getData());
            }
            $form->setValues($formValues);
            return parent::_prepareForm();
        }












    }
}
