<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document edit form tab
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Block_Adminhtml_Otherdoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Otherdoc_Block_Adminhtml_Otherdoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('otherdoc_');
        $form->setFieldNameSuffix('otherdoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'otherdoc_form',
            array('legend' => Mage::helper('bs_otherdoc')->__('Other\'s Course Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_otherdoc/adminhtml_otherdoc_helper_file')
        );

        $fieldset->addField(
            'otherdoc_name',
            'text',
            array(
                'label' => Mage::helper('bs_otherdoc')->__('Document Name'),
                'name'  => 'otherdoc_name',

           )
        );


        $fieldset->addField(
            'otherdoc_type',
            'select',
            array(
                'label' => Mage::helper('bs_otherdoc')->__('Document Type'),
                'name'  => 'otherdoc_type',

                'values'=> Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdoctype')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'otherdoc_date',
            'date',
            array(
                'label' => Mage::helper('bs_otherdoc')->__('Date'),
                'name'  => 'otherdoc_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'otherdoc_file',
            'file',
            array(
                'label' => Mage::helper('bs_otherdoc')->__('File'),
                'name'  => 'otherdoc_file',

           )
        );

        $fieldset->addField(
            'otherdoc_rev',
            'select',
            array(
                'label' => Mage::helper('bs_otherdoc')->__('Revision'),
                'name'  => 'otherdoc_rev',

            'values'=> Mage::getModel('bs_otherdoc/otherdoc_attribute_source_otherdocrev')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_otherdoc')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_otherdoc')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_otherdoc')->__('Disabled'),
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

        $formValues = Mage::registry('current_otherdoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getOtherdocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getOtherdocData());
            Mage::getSingleton('adminhtml/session')->setOtherdocData(null);
        } elseif (Mage::registry('current_otherdoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_otherdoc')->getData());
        }
        if($courseId){
            $formValues = array_merge($formValues, array('hidden_course_id' => $courseId));
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {

        if(Mage::registry('current_otherdoc')->getId()){
            $html .= "<script>

                    if($('otherdoc_otherdoc_type') != undefined){
                        $('otherdoc_otherdoc_type').up('tr').setStyle({'display':'none'});
                    }


                </script>";
        }

        return parent::_afterToHtml($html);
    }
}
