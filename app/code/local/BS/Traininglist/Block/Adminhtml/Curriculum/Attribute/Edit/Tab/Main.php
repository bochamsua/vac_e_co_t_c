<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml training curriculum attribute edit page main tab
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_Attribute_Edit_Tab_Main extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Main_Abstract
{
    /**
     * Adding product form elements for editing attribute
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Attribute_Edit_Tab_Main
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $attributeObject = $this->getAttributeObject();
        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $frontendInputElm = $form->getElement('frontend_input');
        $additionalTypes = array(
            array(
                'value' => 'image',
                'label' => Mage::helper('bs_traininglist')->__('Image')
            ),
            array(
                'value' => 'file',
                'label' => Mage::helper('bs_traininglist')->__('File')
            )
        );
        $response = new Varien_Object();
        $response->setTypes(array());
        Mage::dispatchEvent('adminhtml_curriculum_attribute_types', array('response'=>$response));
        $_disabledTypes = array();
        $_hiddenFields = array();
        foreach ($response->getTypes() as $type) {
            $additionalTypes[] = $type;
            if (isset($type['hide_fields'])) {
                $_hiddenFields[$type['value']] = $type['hide_fields'];
            }
            if (isset($type['disabled_types'])) {
                $_disabledTypes[$type['value']] = $type['disabled_types'];
            }
        }
        Mage::register('attribute_type_hidden_fields', $_hiddenFields);
        Mage::register('attribute_type_disabled_types', $_disabledTypes);

        $frontendInputValues = array_merge($frontendInputElm->getValues(), $additionalTypes);
        $frontendInputElm->setValues($frontendInputValues);

        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();

        $scopes = array(
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                Mage::helper('bs_traininglist')->__('Store View'),
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                Mage::helper('bs_traininglist')->__('Website'),
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                Mage::helper('bs_traininglist')->__('Global'),
        );

        $fieldset->addField(
            'is_global',
            'select',
            array(
                'name'  => 'is_global',
                'label' => Mage::helper('bs_traininglist')->__('Scope'),
                'title' => Mage::helper('bs_traininglist')->__('Scope'),
                'note'  => Mage::helper('bs_traininglist')->__('Declare attribute value saving scope'),
                'values'=> $scopes
            ),
            'attribute_code'
        );
        $fieldset->addField(
            'position',
            'text',
            array(
                'name'  => 'position',
                'label' => Mage::helper('bs_traininglist')->__('Position'),
                'title' => Mage::helper('bs_traininglist')->__('Position'),
                'note'  => Mage::helper('bs_traininglist')->__('Position in the admin form'),
            ),
            'is_global'
        );
        $fieldset->addField(
            'note',
            'textarea',
            array(
                'name'  => 'note',
                'label' => Mage::helper('bs_traininglist')->__('Note'),
                'title' => Mage::helper('bs_traininglist')->__('Note'),
                'note'  => Mage::helper('bs_traininglist')->__('Text to appear below the input.'),
            ),
            'position'
        );

        $fieldset->removeField('is_unique');
        // frontend properties fieldset
        $fieldset = $form->addFieldset(
            'front_fieldset',
            array(
                'legend'=>Mage::helper('bs_traininglist')->__('Frontend Properties')
            )
        );
        $fieldset->addField(
            'is_wysiwyg_enabled',
            'select',
            array(
                'name' => 'is_wysiwyg_enabled',
                'label' => Mage::helper('bs_traininglist')->__('Enable WYSIWYG'),
                'title' => Mage::helper('bs_traininglist')->__('Enable WYSIWYG'),
                'values' => $yesnoSource,
            )
        );
        Mage::dispatchEvent(
            'bs_traininglist_adminhtml_curriculum_attribute_edit_prepare_form',
            array(
                'form'      => $form,
                'attribute' => $attributeObject
            )
        );
        return $this;
    }
}
