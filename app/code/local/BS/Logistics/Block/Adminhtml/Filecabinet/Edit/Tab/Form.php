<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Cabinet edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filecabinet_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Filecabinet_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('filecabinet_');
        $form->setFieldNameSuffix('filecabinet');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'filecabinet_form',
            array('legend' => Mage::helper('bs_logistics')->__('File Cabinet'))
        );
        $values = Mage::getResourceModel('bs_logistics/otherroom_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="filecabinet_otherroom_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeOtherroomIdLink() {
                if ($(\'filecabinet_otherroom_id\').value == \'\') {
                    $(\'filecabinet_otherroom_id_link\').hide();
                } else {
                    $(\'filecabinet_otherroom_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_otherroom/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'filecabinet_otherroom_id\').value);
                    $(\'filecabinet_otherroom_id_link\').href = realUrl;
                    $(\'filecabinet_otherroom_id_link\').innerHTML = text.replace(\'{#name}\', $(\'filecabinet_otherroom_id\').options[$(\'filecabinet_otherroom_id\').selectedIndex].innerHTML);
                }
            }
            $(\'filecabinet_otherroom_id\').observe(\'change\', changeOtherroomIdLink);
            changeOtherroomIdLink();
            </script>';



        $fieldset->addField(
            'filecabinet_name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('File Cabinet Name'),
                'name'  => 'filecabinet_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'filecabinet_code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'filecabinet_code',

           )
        );

        $fieldset->addField(
            'filecabinet_stack',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Number of Stacks'),
                'name'  => 'filecabinet_stack',

           )
        );

        $fieldset->addField(
            'otherroom_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Room'),
                'name'      => 'otherroom_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'filecabinet_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'filecabinet_note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_logistics')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_logistics')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_logistics')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_filecabinet')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getFilecabinetData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFilecabinetData());
            Mage::getSingleton('adminhtml/session')->setFilecabinetData(null);
        } elseif (Mage::registry('current_filecabinet')) {
            $formValues = array_merge($formValues, Mage::registry('current_filecabinet')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
