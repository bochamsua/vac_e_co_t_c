<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Group edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstgroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstgroup_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('kstgroup_');
        $form->setFieldNameSuffix('kstgroup');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'kstgroup_form',
            array('legend' => Mage::helper('bs_kst')->__('Group'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Group Name'),
                'name'  => 'name',

           )
        );



        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('status',1)
            ->addAttributeToFilter('sku',array(array('like'=>'%KST%'), array('like'=>'%CRS%')))
            ->addAttributeToFilter('course_report',0);




        $values = $values->toSkuOptionArray();

        $fieldset->addField(
            'course_id',
            'select',
            array(
                'label'     => Mage::helper('bs_kst')->__('Course'),
                'name'      => 'course_id',
                'required'  => true,
                'class' => 'required-entry',
                'values'    => $values,
            )
        );


        $fieldset->addField(
            'note',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_kst')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_kst')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_kst')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_kstgroup')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getKstgroupData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getKstgroupData());
            Mage::getSingleton('adminhtml/session')->setKstgroupData(null);
        } elseif (Mage::registry('current_kstgroup')) {
            $formValues = array_merge($formValues, Mage::registry('current_kstgroup')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
