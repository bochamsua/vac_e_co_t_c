<?php
 /**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin edit tab attributes block
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
*/
class BS_Instructor_Block_Adminhtml_Instructor_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the attributes for the form
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setDataObject(Mage::registry('current_instructor'));
        $fieldset = $form->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('bs_instructor')->__('Instructor Information'),
                'class' => 'fieldset-wide',
            )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity(Mage::getResourceModel('bs_instructor/instructor'));
        }
        $this->_setFieldset($attributes, $fieldset, array());
        $formValues = Mage::registry('current_instructor')->getData();
        if (!Mage::registry('current_instructor')->getId()) {
            foreach ($attributes as $attribute) {
                if (!isset($formValues[$attribute->getAttributeCode()])) {
                    $formValues[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                }
            }
        }
        $form->addValues($formValues);
        $form->setFieldNameSuffix('instructor');
        $this->setForm($form);
    }

    /**
     * prepare layout
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareLayout()
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('bs_instructor/adminhtml_instructor_renderer_fieldset_element')
        );
    }

    /**
     * get the additional element types for form
     *
     * @access protected
     * @return array()
     * @see Mage_Adminhtml_Block_Widget_Form::_getAdditionalElementTypes()
     * @author Bui Phong
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'file'     => Mage::getConfig()->getBlockClassName(
                'bs_instructor/adminhtml_instructor_helper_file'
            ),
            'image'    => Mage::getConfig()->getBlockClassName(
                'bs_instructor/adminhtml_instructor_helper_image'
            ),
            'textarea' => Mage::getConfig()->getBlockClassName(
                'adminhtml/catalog_helper_form_wysiwyg'
            )
        );
    }

    /**
     * get current entity
     *
     * @access protected
     * @return BS_Instructor_Model_Instructor
     * @author Bui Phong
     */
    public function getInstructor()
    {
        return Mage::registry('current_instructor');
    }
}
