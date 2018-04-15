<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Equipment edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Equipment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Equipment_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('equipment_');
        $form->setFieldNameSuffix('equipment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'equipment_form',
            array('legend' => Mage::helper('bs_logistics')->__('Equipment'))
        );
        $values = Mage::getResourceModel('bs_logistics/classroom_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="equipment_classroom_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeClassroomIdLink() {
                if ($(\'equipment_classroom_id\').value == \'\') {
                    $(\'equipment_classroom_id_link\').hide();
                } else {
                    $(\'equipment_classroom_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_classroom/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'equipment_classroom_id\').value);
                    $(\'equipment_classroom_id_link\').href = realUrl;
                    $(\'equipment_classroom_id_link\').innerHTML = text.replace(\'{#name}\', $(\'equipment_classroom_id\').options[$(\'equipment_classroom_id\').selectedIndex].innerHTML);
                }
            }
            $(\'equipment_classroom_id\').observe(\'change\', changeClassroomIdLink);
            changeClassroomIdLink();
            </script>';

        $fieldset->addField(
            'classroom_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Classroom/Examroom'),
                'name'      => 'classroom_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );
        $values = Mage::getResourceModel('bs_logistics/workshop_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="equipment_workshop_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeWorkshopIdLink() {
                if ($(\'equipment_workshop_id\').value == \'\') {
                    $(\'equipment_workshop_id_link\').hide();
                } else {
                    $(\'equipment_workshop_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_workshop/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'equipment_workshop_id\').value);
                    $(\'equipment_workshop_id_link\').href = realUrl;
                    $(\'equipment_workshop_id_link\').innerHTML = text.replace(\'{#name}\', $(\'equipment_workshop_id\').options[$(\'equipment_workshop_id\').selectedIndex].innerHTML);
                }
            }
            $(\'equipment_workshop_id\').observe(\'change\', changeWorkshopIdLink);
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
        $values = Mage::getResourceModel('bs_logistics/otherroom_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="equipment_otherroom_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeOtherroomIdLink() {
                if ($(\'equipment_otherroom_id\').value == \'\') {
                    $(\'equipment_otherroom_id_link\').hide();
                } else {
                    $(\'equipment_otherroom_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_otherroom/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'equipment_otherroom_id\').value);
                    $(\'equipment_otherroom_id_link\').href = realUrl;
                    $(\'equipment_otherroom_id_link\').innerHTML = text.replace(\'{#name}\', $(\'equipment_otherroom_id\').options[$(\'equipment_otherroom_id\').selectedIndex].innerHTML);
                }
            }
            $(\'equipment_otherroom_id\').observe(\'change\', changeOtherroomIdLink);
            changeOtherroomIdLink();
            </script>';

        $fieldset->addField(
            'otherroom_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Other room'),
                'name'      => 'otherroom_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'equipment_name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'equipment_name',
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
            'qty',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Qty'),
                'name'  => 'qty',

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
        $formValues = Mage::registry('current_equipment')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getEquipmentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getEquipmentData());
            Mage::getSingleton('adminhtml/session')->setEquipmentData(null);
        } elseif (Mage::registry('current_equipment')) {
            $formValues = array_merge($formValues, Mage::registry('current_equipment')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
