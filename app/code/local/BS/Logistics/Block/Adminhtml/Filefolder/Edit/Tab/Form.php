<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('filefolder_');
        $form->setFieldNameSuffix('filefolder');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'filefolder_form',
            array('legend' => Mage::helper('bs_logistics')->__('File Folder'))
        );
        $values = Mage::getResourceModel('bs_logistics/filecabinet_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="filefolder_filecabinet_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeFilecabinetIdLink() {
                if ($(\'filefolder_filecabinet_id\').value == \'\') {
                    $(\'filefolder_filecabinet_id_link\').hide();
                } else {
                    $(\'filefolder_filecabinet_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_filecabinet/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'filefolder_filecabinet_id\').value);
                    $(\'filefolder_filecabinet_id_link\').href = realUrl;
                    $(\'filefolder_filecabinet_id_link\').innerHTML = text.replace(\'{#name}\', $(\'filefolder_filecabinet_id\').options[$(\'filefolder_filecabinet_id\').selectedIndex].innerHTML);
                }
            }
            $(\'filefolder_filecabinet_id\').observe(\'change\', changeFilecabinetIdLink);
            changeFilecabinetIdLink();
            </script>';

        $fieldset->addField(
            'filecabinet_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('File Cabinet'),
                'name'      => 'filecabinet_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'filefolder_name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'filefolder_name',
            'note'	=> $this->__('For example: For Type-Training Courses'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'filefolder_code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'filefolder_code',
            'note'	=> $this->__('Enter code here. For example: P1-01'),

           )
        );

        $fieldset->addField(
            'filefolder_note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'filefolder_note',

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
        $formValues = Mage::registry('current_filefolder')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getFilefolderData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFilefolderData());
            Mage::getSingleton('adminhtml/session')->setFilefolderData(null);
        } elseif (Mage::registry('current_filefolder')) {
            $formValues = array_merge($formValues, Mage::registry('current_filefolder')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
