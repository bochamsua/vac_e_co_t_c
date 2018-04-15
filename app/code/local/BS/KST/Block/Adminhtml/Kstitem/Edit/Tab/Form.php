<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstitem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstitem_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('kstitem_');
        $form->setFieldNameSuffix('kstitem');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'kstitem_form',
            array('legend' => Mage::helper('bs_kst')->__('Item'))
        );
        $values = Mage::getResourceModel('bs_kst/kstsubject_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="kstitem_kstsubject_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeKstsubjectIdLink() {
                if ($(\'kstitem_kstsubject_id\').value == \'\') {
                    $(\'kstitem_kstsubject_id_link\').hide();
                } else {
                    $(\'kstitem_kstsubject_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/kst_kstsubject/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'kstitem_kstsubject_id\').value);
                    $(\'kstitem_kstsubject_id_link\').href = realUrl;
                    $(\'kstitem_kstsubject_id_link\').innerHTML = text.replace(\'{#name}\', $(\'kstitem_kstsubject_id\').options[$(\'kstitem_kstsubject_id\').selectedIndex].innerHTML);
                }
            }
            $(\'kstitem_kstsubject_id\').observe(\'change\', changeKstsubjectIdLink);
            changeKstsubjectIdLink();
            </script>';

        $fieldset->addField(
            'kstsubject_id',
            'select',
            array(
                'label'     => Mage::helper('bs_kst')->__('Subject'),
                'name'      => 'kstsubject_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'ref',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Reference Doc'),
                'name'  => 'ref',

           )
        );

        $fieldset->addField(
            'taskcode',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Task Code'),
                'name'  => 'taskcode',

           )
        );

        $fieldset->addField(
            'taskcat',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Task Cat'),
                'name'  => 'taskcat',

           )
        );

        $fieldset->addField(
            'applicable_for',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Applicable For'),
                'name'  => 'applicable_for',

           )
        );

        $fieldset->addField(
            'position',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Position'),
                'name'  => 'position',

           )
        );

        $formValues = Mage::registry('current_kstitem')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getKstitemData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getKstitemData());
            Mage::getSingleton('adminhtml/session')->setKstitemData(null);
        } elseif (Mage::registry('current_kstitem')) {
            $formValues = array_merge($formValues, Mage::registry('current_kstitem')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
