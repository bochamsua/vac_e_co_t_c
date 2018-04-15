<?php
 /**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family admin edit tab attributes block
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
*/
class BS_Tc_Block_Adminhtml_Family_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
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
        $form->setDataObject(Mage::registry('current_family'));
        $fieldset = $form->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('bs_tc')->__('Family Information'),
                'class' => 'fieldset-wide',
            )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity(Mage::getResourceModel('bs_tc/family'));
        }
        $this->_setFieldset($attributes, $fieldset, array());
        $formValues = Mage::registry('current_family')->getData();
        if (!Mage::registry('current_family')->getId()) {
            foreach ($attributes as $attribute) {
                if (!isset($formValues[$attribute->getAttributeCode()])) {
                    $formValues[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                }
            }
        }
        $form->addValues($formValues);
        $form->setFieldNameSuffix('family');
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
            $this->getLayout()->createBlock('bs_tc/adminhtml_tc_renderer_fieldset_element')
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
                'bs_tc/adminhtml_family_helper_file'
            ),
            'image'    => Mage::getConfig()->getBlockClassName(
                'bs_tc/adminhtml_family_helper_image'
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
     * @return BS_Tc_Model_Family
     * @author Bui Phong
     */
    public function getFamily()
    {
        return Mage::registry('current_family');
    }

    /**
     * get after element html
     *
     * @access protected
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     * @author Bui Phong
     */
    protected function _getAdditionalElementHtml($element)
    {
        if ($element->getName() == 'employee_id') {
            $html = '<a href="{#url}" id="employee_id_link" target="_blank"></a>';
            $html .= '<script type="text/javascript">
            function changeEmployeeIdLink() {
                if ($(\'employee_id\').value == \'\') {
                    $(\'employee_id_link\').hide();
                } else {
                    $(\'employee_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/tc_employee/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'employee_id\').value);
                    $(\'employee_id_link\').href = realUrl;
                    $(\'employee_id_link\').innerHTML = text.replace(\'{#name}\', $(\'employee_id\').options[$(\'employee_id\').selectedIndex].innerHTML);
                }
            }
            $(\'employee_id\').observe(\'change\', changeEmployeeIdLink);
            changeEmployeeIdLink();
            </script>';
            return $html;
        }
        return '';
    }
}
