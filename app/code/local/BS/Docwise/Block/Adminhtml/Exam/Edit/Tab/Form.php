<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('exam_');
        $form->setFieldNameSuffix('exam');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'exam_form',
            array('legend' => Mage::helper('bs_docwise')->__('Exam'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_docwise/adminhtml_exam_helper_file')
        );

        $fieldset->addField(
            'exam_code',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Code'),
                'name'  => 'exam_code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'requested_date',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Requested Date'),
                'name'  => 'requested_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );




        $fieldset->addField(
            'exam_type',
            'select',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exam Type'),
                'name'  => 'exam_type',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('bs_docwise/exam_attribute_source_examtype')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'cert_type',
            'select',
            array(
                'label' => Mage::helper('bs_docwise')->__('Certificate Type'),
                'name'  => 'cert_type',
                'required'  => true,
                'class' => 'required-entry',


                'values'=> Mage::getModel('bs_docwise/exam_attribute_source_certtype')->getAllOptions(true),
                'note'  => ''
           )
        );

        $fieldset->addField(
            'number_trainee',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Number Trainee'),
                'name'  => 'number_trainee',
                'required'  => true,
                'class' => 'required-entry',

            )
        );

        $fieldset->addField(
            'exam_request_dept',
            'multiselect',
            array(
                'label' => Mage::helper('bs_docwise')->__('Requested Department'),
                'name'  => 'exam_request_dept',

            'values'=> Mage::getModel('bs_docwise/exam_attribute_source_examrequestdept')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'request_file',
            'file',
            array(
                'label' => Mage::helper('bs_docwise')->__('Proof Message'),
                'name'  => 'request_file',

           )
        );

        $fieldset->addField(
            'exam_date',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exam Date'),
                'name'  => 'exam_date',

                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );
        /*$fieldset->addField(
            'easa',
            'select',
            array(
                'label'  => Mage::helper('bs_docwise')->__('EASA?'),
                'name'   => 'easa',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_docwise')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_docwise')->__('No'),
                    ),
                ),
            )
        );*/

        $fieldset->addField(
            'examiner',
            'multiselect',
            array(
                'label' => Mage::helper('bs_docwise')->__('Examiner'),
                'name'  => 'examiner',

                'values'=> Mage::getModel('bs_docwise/exam_attribute_source_examiner')->getAllOptions(true),
            )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_docwise')->__('Note'),
                'name'  => 'note',

            )
        );


        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_docwise')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_docwise')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_docwise')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_exam')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getExamData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getExamData());
            Mage::getSingleton('adminhtml/session')->setExamData(null);
        } elseif (Mage::registry('current_exam')) {
            $formValues = array_merge($formValues, Mage::registry('current_exam')->getData());
            if(!Mage::registry('current_exam')->getId()){

                $code = 'TOEFA/'.date('y');

                $allExams = Mage::getModel('bs_docwise/exam')->getCollection()->addFieldToFilter('exam_code', array('like'=>$code.'%'));
                $count = count($allExams);
                $next = $count + 1;
                if($next < 10){
                    $next = '00'.$next;
                }elseif ($next < 100 && $next >= 10){
                    $next = '0'.$next;
                }

                $formValues = array_merge($formValues, array('exam_code'=>$code.$next));
            }

        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
