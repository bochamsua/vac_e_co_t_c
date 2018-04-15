<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document edit form tab
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Material_Block_Adminhtml_Instructordoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('instructordoc_');
        $form->setFieldNameSuffix('instructordoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'instructordoc_form',
            array('legend' => Mage::helper('bs_material')->__('Instructor Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_material/adminhtml_instructordoc_helper_file')
        );

        $fieldset->addField(
            'idoc_name',
            'text',
            array(
                'label' => Mage::helper('bs_material')->__('Document Name'),
                'name' => 'idoc_name',
                'required' => true,
                'class' => 'required-entry',

            )
        );


        $fieldset->addField(
            'idoc_type',
            'select',
            array(
                'label' => Mage::helper('bs_material')->__('Document Type'),
                'name'  => 'idoc_type',

                'values'=> Mage::getModel('bs_material/instructordoc_attribute_source_idoctype')->getAllOptions(true),
            )

        );


        $fieldset->addField(
            'idoc_file',
            'file',
            array(
                'label' => Mage::helper('bs_material')->__('File'),
                'name'  => 'idoc_file',

           )
        );

        $fieldset->addField(
            'idoc_date',
            'date',
            array(
                'label' => Mage::helper('bs_material')->__('Approved/Revised Date'),
                'name'  => 'idoc_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            )
        );

        $fieldset->addField(
            'idoc_rev',
            'select',
            array(
                'label' => Mage::helper('bs_material')->__('Revision'),
                'name'  => 'idoc_rev',

            'values'=> Mage::getModel('bs_material/instructordoc_attribute_source_idocrev')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_material')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_material')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_material')->__('Disabled'),
                    ),
                ),
            )
        );

        $instructorId = $this->getRequest()->getParam('instructor_id', false);
        $fieldset->addField(
            'hidden_instructor_id',
            'text',
            array(
                'label' => Mage::helper('bs_material')->__('Hidden Instructor Id'),
                'name'  => 'hidden_instructor_id',


            )
        );

        $formValues = Mage::registry('current_instructordoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getInstructordocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInstructordocData());
            Mage::getSingleton('adminhtml/session')->setInstructordocData(null);
        } elseif (Mage::registry('current_instructordoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_instructordoc')->getData());
        }
        if($instructorId){
            $formValues = array_merge($formValues, array('hidden_instructor_id' => $instructorId));
        }

        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {

        if(Mage::registry('current_instructordoc')->getId()){
            $html .= "<script>

                    if($('current_instructordoc') != undefined){
                        $('current_instructordoc').up('tr').setStyle({'display':'none'});
                    }


                </script>";
        }

        return parent::_afterToHtml($html);
    }
}
