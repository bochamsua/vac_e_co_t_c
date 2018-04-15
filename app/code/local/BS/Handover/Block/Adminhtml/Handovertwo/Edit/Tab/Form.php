<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V2 edit form tab
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handovertwo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handovertwo_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('handovertwo_');
        $form->setFieldNameSuffix('handovertwo');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'handovertwo_form',
            array('legend' => Mage::helper('bs_handover')->__('Minutes of Handover V2'))
        );

        $fieldset->addField(
            'course_id',
            'text',
            array(
                'label' => Mage::helper('bs_handover')->__('Course'),
                'name'  => 'course_id',

           )
        );

        $fieldset->addField(
            'receiver',
            'text',
            array(
                'label' => Mage::helper('bs_handover')->__('Receiver'),
                'name'  => 'receiver',

            )
        );

        $fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_handover')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_handover')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_handover')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_handover')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_handovertwo')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getHandovertwoData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getHandovertwoData());
            Mage::getSingleton('adminhtml/session')->setHandovertwoData(null);
        } elseif (Mage::registry('current_handovertwo')) {
            $formValues = array_merge($formValues, Mage::registry('current_handovertwo')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
