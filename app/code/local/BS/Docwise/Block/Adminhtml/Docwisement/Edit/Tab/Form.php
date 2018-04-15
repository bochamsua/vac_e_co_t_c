<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Docwise Document edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Docwisement_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Docwisement_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('docwisement_');
        $form->setFieldNameSuffix('docwisement');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'docwisement_form',
            array('legend' => Mage::helper('bs_docwise')->__('Docwise Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_docwise/adminhtml_docwisement_helper_file')
        );

        $fieldset->addField(
            'doc_name',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Document Name'),
                'name'  => 'doc_name',

           )
        );

        $fieldset->addField(
            'doc_type',
            'select',
            array(
                'label' => Mage::helper('bs_docwise')->__('Document Type'),
                'name'  => 'doc_type',

            'values'=> Mage::getModel('bs_docwise/docwisement_attribute_source_doctype')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'doc_date',
            'date',
            array(
                'label' => Mage::helper('bs_docwise')->__('Date'),
                'name'  => 'doc_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'doc_file',
            'file',
            array(
                'label' => Mage::helper('bs_docwise')->__('File'),
                'name'  => 'doc_file',

           )
        );

        $examId = $this->getRequest()->getParam('exam_id', false);
        $fieldset->addField(
            'exam_id',
            'text',
            array(
                'label' => Mage::helper('bs_material')->__('Hidden Exam Id'),
                'name'  => 'exam_id',


            )
        );
        /*
        $fieldset->addField(
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
        $formValues = Mage::registry('current_docwisement')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getDocwisementData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getDocwisementData());
            Mage::getSingleton('adminhtml/session')->setDocwisementData(null);
        } elseif (Mage::registry('current_docwisement')) {
            $formValues = array_merge($formValues, Mage::registry('current_docwisement')->getData());
        }

        if($examId){
            $formValues = array_merge($formValues, array('exam_id' => $examId));
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>

                    if($('docwisement_exam_id') != undefined){
                        $('docwisement_exam_id').up('tr').setStyle({'display':'none'});
                    }


                </script>";

        return parent::_afterToHtml($html);
    }
}
