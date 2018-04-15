<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstsubject_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstsubject_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('kstsubject_');
        $form->setFieldNameSuffix('kstsubject');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'kstsubject_form',
            array('legend' => Mage::helper('bs_kst')->__('Subject'))
        );

        $currentSubject = Mage::registry('current_kstsubject');
        $curriculumId = null;
        if($this->getRequest()->getParam('curriculum_id')){
            $curriculumId = $this->getRequest()->getParam('curriculum_id');
        }elseif ($currentSubject){
            $curriculumId = $currentSubject->getCurriculumId();
        }


        $values = Mage::getResourceModel('bs_traininglist/curriculum_collection')->addAttributeToFilter('c_history', 0)->addAttributeToFilter('c_code',array(array('like'=>'%KST%'), array('like'=>'%CRS%')));
        if($curriculumId){
            $values->addFieldToFilter('entity_id', $curriculumId);
        }
        $values = $values->toOptionCodeArray();


        $fieldset->addField(
            'curriculum_id',
            'select',
            array(
                'label'     => Mage::helper('bs_kst')->__('Curriculum'),
                'name'      => 'curriculum_id',
                'required'  => false,
                'values'    => $values,

            )
        );



        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Name'),
                'name'  => 'name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );



        $fieldset->addField(
            'position',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Position'),
                'name'  => 'position',

           )
        );

        $fieldset->addField(
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_kst')->__('Import'),
                'name'  => 'import',
                'note'  => 'Format can be accepted in 2 formats:<br> 1. Item == Ref Document == Task Code == Task Cat == Applicable for<br> 2. Item == Ref Document == Task Code == Task Cat == A/C Reg == Date == Trainee == Instructor == Applicable for <br>"==" can be TAB. <br>If row has valid COUNT = 1, it will be treated as Subject.'

            )
        );

        $fieldset->addField(
            'clearall',
            'select',
            array(
                'label'  => Mage::helper('bs_kst')->__('Clear all existing content?'),
                'name'   => 'clearall',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_kst')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_kst')->__('No'),
                    ),
                ),
                'note'  => 'Please note that all existing content will be overwitten if you select Yes.'
            )
        );




        $formValues = Mage::registry('current_kstsubject')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getKstsubjectData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getKstsubjectData());
            Mage::getSingleton('adminhtml/session')->setKstsubjectData(null);
        } elseif (Mage::registry('current_kstsubject')) {
            $formValues = array_merge($formValues, Mage::registry('current_kstsubject')->getData());
        }
        $form->setValues($formValues);


        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('kstsubject_import') != undefined){
                        $('kstsubject_import').observe('keyup', function(){
                            kstsubject_name.setValue('This will be ignored');
                        });
                    }



                </script>";
        return parent::_afterToHtml($html);
    }
}
