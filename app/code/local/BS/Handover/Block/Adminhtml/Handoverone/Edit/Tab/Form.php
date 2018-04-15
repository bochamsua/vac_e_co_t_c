<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V1 edit form tab
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handoverone_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Handover_Block_Adminhtml_Handoverone_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('handoverone_');
        $form->setFieldNameSuffix('handoverone');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'handoverone_form',
            array('legend' => Mage::helper('bs_handover')->__('Minutes of Handover V1'))
        );

        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('bs_handover')->__('Title'),
                'name'  => 'title',

           )
        );

        $fieldset->addField(
            'send_date',
            'date',
            array(
                'label' => Mage::helper('bs_handover')->__('Date'),
                'name'  => 'send_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'content',
            'textarea',
            array(
                'label' => Mage::helper('bs_handover')->__('Content'),
                'name'  => 'content',
            'note'	=> $this->__('Enter content one by one on each row. Format: Item --- qty --- note'),

           )
        );

        $fieldset->addField(
            'receiver',
            'text',
            array(
                'label' => Mage::helper('bs_handover')->__('Receiver'),
                'name'  => 'receiver',
                'note'	=> $this->__('Enter VAECO ID'),

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
        $formValues = Mage::registry('current_handoverone')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getHandoveroneData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getHandoveroneData());
            Mage::getSingleton('adminhtml/session')->setHandoveroneData(null);
        } elseif (Mage::registry('current_handoverone')) {
            $formValues = array_merge($formValues, Mage::registry('current_handoverone')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
