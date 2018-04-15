<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Group Items edit form tab
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Costitem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Costitem_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('costitem_');
        $form->setFieldNameSuffix('costitem');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'costitem_form',
            array('legend' => Mage::helper('bs_coursecost')->__('Manage Group Items'))
        );
        $values = Mage::getResourceModel('bs_coursecost/costgroup_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));



        $fieldset->addField(
            'costgroup_id',
            'select',
            array(
                'label'     => Mage::helper('bs_coursecost')->__('Manage Cost Group'),
                'name'      => 'costgroup_id',
                'required'  => false,
                'values'    => $values,
            )
        );

        $fieldset->addField(
            'item_name',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Item Name'),
                'name'  => 'item_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'item_cost',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Cost'),
                'name'  => 'item_cost',
                'note'  => Mage::helper('bs_coursecost')->__('Please use a thousand (1000) VNĐ as base unit'),

           )
        );

        $fieldset->addField(
            'item_unit',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Unit'),
                'name'  => 'item_unit',
                'note'  => Mage::helper('bs_coursecost')->__('Can be "cái", "hộp", "ram giấy" ...'),


           )
        );

        /*$fieldset->addField(
            'update_date',
            'date',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Date'),
                'name'  => 'update_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_coursecost')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_coursecost')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_coursecost')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_costitem')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCostitemData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCostitemData());
            Mage::getSingleton('adminhtml/session')->setCostitemData(null);
        } elseif (Mage::registry('current_costitem')) {
            $formValues = array_merge($formValues, Mage::registry('current_costitem')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
