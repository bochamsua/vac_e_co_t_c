<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wgroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wgroup_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('wgroup_');
        $form->setFieldNameSuffix('wgroup');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'wgroup_form',
            array('legend' => Mage::helper('bs_logistics')->__('Group'))
        );
        $values = Mage::getResourceModel('bs_logistics/workshop_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="wgroup_workshop_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeWorkshopIdLink() {
                if ($(\'wgroup_workshop_id\').value == \'\') {
                    $(\'wgroup_workshop_id_link\').hide();
                } else {
                    $(\'wgroup_workshop_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_workshop/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wgroup_workshop_id\').value);
                    $(\'wgroup_workshop_id_link\').href = realUrl;
                    $(\'wgroup_workshop_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wgroup_workshop_id\').options[$(\'wgroup_workshop_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wgroup_workshop_id\').observe(\'change\', changeWorkshopIdLink);
            changeWorkshopIdLink();
            </script>';

        $fieldset->addField(
            'workshop_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Workshop'),
                'name'      => 'workshop_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );
        $values = Mage::getResourceModel('bs_logistics/grouptype_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="wgroup_grouptype_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeGrouptypeIdLink() {
                if ($(\'wgroup_grouptype_id\').value == \'\') {
                    $(\'wgroup_grouptype_id_link\').hide();
                } else {
                    $(\'wgroup_grouptype_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_grouptype/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wgroup_grouptype_id\').value);
                    $(\'wgroup_grouptype_id_link\').href = realUrl;
                    $(\'wgroup_grouptype_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wgroup_grouptype_id\').options[$(\'wgroup_grouptype_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wgroup_grouptype_id\').observe(\'change\', changeGrouptypeIdLink);
            changeGrouptypeIdLink();
            </script>';

        $fieldset->addField(
            'grouptype_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Type'),
                'name'      => 'grouptype_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'name_vi',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Vietnamese'),
                'name'  => 'name_vi',

           )
        );

        $fieldset->addField(
            'code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'code',

           )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'note',

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
        $formValues = Mage::registry('current_wgroup')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWgroupData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWgroupData());
            Mage::getSingleton('adminhtml/session')->setWgroupData(null);
        } elseif (Mage::registry('current_wgroup')) {
            $formValues = array_merge($formValues, Mage::registry('current_wgroup')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
