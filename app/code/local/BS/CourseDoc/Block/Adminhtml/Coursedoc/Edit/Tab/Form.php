<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document edit form tab
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Block_Adminhtml_Coursedoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_CourseDoc_Block_Adminhtml_Coursedoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('coursedoc_');
        $form->setFieldNameSuffix('coursedoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'coursedoc_form',
            array('legend' => Mage::helper('bs_coursedoc')->__('Course Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_coursedoc/adminhtml_coursedoc_helper_file')
        );

        $fieldset->addField(
            'course_doc_name',
            'text',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Document Name'),
                'name'  => 'course_doc_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'doc_code',
            'text',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Document Code'),
                'name'  => 'doc_code',

            )
        );

        $fieldset->addField(
            'doc_inorout',
            'select',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('In or Out'),
                'name'  => 'doc_inorout',

                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_coursedoc')->__('In Coming'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_coursedoc')->__('Out Going'),
                    ),
                ),
            )
        );




        $fieldset->addField(
            'course_doc_type',
            'select',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Document Type'),
                'name'  => 'course_doc_type',

                'values'=> Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedoctype')->getAllOptions(true),
                'note'  => Mage::helper('bs_coursedoc')->__('After saving, you CANNOT edit this field!'),
           )
        );

        $fieldset->addField(
            'doc_dept',
            'select',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Department'),
                'name'  => 'doc_dept',

                'values'=> Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedocdept')->getAllOptions(true),
            )
        );

        $fieldset->addField(
            'doc_dev_other',
            'text',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Other Dept'),
                'name'  => 'doc_dev_other',


            )
        );

        $fieldset->addField(
            'doc_date',
            'date',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Date'),
                'name'  => 'doc_date',
                'note'	=> $this->__('The date of the document'),
                'required'  => true,
                'class' => 'required-entry',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        $fieldset->addField(
            'course_doc_file',
            'file',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('File'),
                'name'  => 'course_doc_file',

           )
        );

        $fieldset->addField(
            'course_doc_rev',
            'select',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Revision'),
                'name'  => 'course_doc_rev',

            'values'=> Mage::getModel('bs_coursedoc/coursedoc_attribute_source_coursedocrev')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_coursedoc')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_coursedoc')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_coursedoc')->__('Disabled'),
                    ),
                ),
            )
        );

        $courseId = $this->getRequest()->getParam('product_id', false);
        $fieldset->addField(
            'hidden_course_id',
            'text',
            array(
                'label' => Mage::helper('bs_coursedoc')->__('Hidden Course Id'),
                'name'  => 'hidden_course_id',


            )
        );

        $formValues = Mage::registry('current_coursedoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCoursedocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCoursedocData());
            Mage::getSingleton('adminhtml/session')->setCoursedocData(null);
        } elseif (Mage::registry('current_coursedoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_coursedoc')->getData());
        }

        if($courseId){
            $formValues = array_merge($formValues, array('hidden_course_id' => $courseId));
        }


        $form->setValues($formValues);

        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap("coursedoc_doc_dept", 'doc_dept')
            ->addFieldMap("coursedoc_doc_dev_other", 'doc_dev_other')
            ->addFieldDependence('doc_dev_other', 'doc_dept', array('322'))
        );
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {

        if(Mage::registry('current_coursedoc')->getId()){
            $html .= "<script>

                    if($('coursedoc_course_doc_type') != undefined){
                        $('coursedoc_course_doc_type').up('tr').setStyle({'display':'none'});
                    }


                </script>";
        }

        return parent::_afterToHtml($html);
    }
}
