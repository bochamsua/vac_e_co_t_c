<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Tool edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wtool_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wtool_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('wtool_');
        $form->setFieldNameSuffix('wtool');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'wtool_form',
            array('legend' => Mage::helper('bs_logistics')->__('Tool'))
        );
        $values = Mage::getResourceModel('bs_logistics/workshop_collection')
            ->toFullOptionArray();


        /*$html = '<a href="{#url}" id="wtool_workshop_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeWorkshopIdLink() {
                if ($(\'wtool_workshop_id\').value == \'\') {
                    $(\'wtool_workshop_id_link\').hide();
                } else {
                    $(\'wtool_workshop_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_workshop/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wtool_workshop_id\').value);
                    $(\'wtool_workshop_id_link\').href = realUrl;
                    $(\'wtool_workshop_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wtool_workshop_id\').options[$(\'wtool_workshop_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wtool_workshop_id\').observe(\'change\', changeWorkshopIdLink);
            changeWorkshopIdLink();
            </script>';*/

        $fieldset->addField(
            'workshop_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Workshop'),
                'name'      => 'workshop_id',
                'required'  => false,
                'values'    => $values,
                //'after_element_html' => $html
            )
        );

        $values = Mage::getResourceModel('bs_logistics/grouptype_collection')
            ->toFullOptionArray();
        //array_unshift($values, array('label' => '', 'value' => ''));

        /*$html = '<a href="{#url}" id="wtool_grouptype_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeGrouptypeIdLink() {
                if ($(\'wtool_grouptype_id\').value == \'\') {
                    $(\'wtool_grouptype_id_link\').hide();
                } else {
                    $(\'wtool_grouptype_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_grouptype/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wtool_grouptype_id\').value);
                    $(\'wtool_grouptype_id_link\').href = realUrl;
                    $(\'wtool_grouptype_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wtool_grouptype_id\').options[$(\'wtool_grouptype_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wtool_grouptype_id\').observe(\'change\', changeGrouptypeIdLink);
            changeGrouptypeIdLink();
            </script>';*/

        $fieldset->addField(
            'grouptype_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Type'),
                'name'      => 'grouptype_id',
                'required'  => false,
                'values'    => $values,
                //'after_element_html' => $html
            )
        );


        $values = Mage::getResourceModel('bs_logistics/wgroupitem_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        /*$html = '<a href="{#url}" id="wtool_wgroupitem_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeWgroupitemIdLink() {
                if ($(\'wtool_wgroupitem_id\').value == \'\') {
                    $(\'wtool_wgroupitem_id_link\').hide();
                } else {
                    $(\'wtool_wgroupitem_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_wgroupitem/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wtool_wgroupitem_id\').value);
                    $(\'wtool_wgroupitem_id_link\').href = realUrl;
                    $(\'wtool_wgroupitem_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wtool_wgroupitem_id\').options[$(\'wtool_wgroupitem_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wtool_wgroupitem_id\').observe(\'change\', changeWgroupitemIdLink);
            changeWgroupitemIdLink();
            </script>';*/

        $fieldset->addField(
            'wgroupitem_id',
            'select',
            array(
                'label'     => Mage::helper('bs_logistics')->__('Equipment'),
                'name'      => 'wgroupitem_id',
                'required'  => false,
                'values'    => $values,
                //'after_element_html' => $html
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
                //'readonly'  => true,
                'note'  => Mage::helper('bs_logistics')->__('This code will be automatically generated after save.'),

            )
        );
        $fieldset->addField(
            'part_number',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('P/N, Type'),
                'name'  => 'part_number',

            )
        );

        $fieldset->addField(
            'tool_status',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Tool Status'),
                'name'  => 'tool_status',

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
            'import',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Or import from this'),
                'name'  => 'import',
                'note'  => Mage::helper('bs_logistics')->__('Put each line in any of following format: <br> 1. Name -- P/N, Type -- Qty<br>2. Name -- Vietnamese Name -- P/N, Type -- Qty <br>3. Name -- Vietnamese Name -- P/N, Type -- Qty -- Status <br> -- can be tab <br> For example: <b>Half-round file--Asaki--2</b>'),

            )
        );
        /*$fieldset->addField(
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
        );*/
        $formValues = Mage::registry('current_wtool')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWtoolData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWtoolData());
            Mage::getSingleton('adminhtml/session')->setWtoolData(null);
        } elseif (Mage::registry('current_wtool')) {
            $formValues = array_merge($formValues, Mage::registry('current_wtool')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('wtool_import') != undefined){
                        $('wtool_import').observe('keyup', function() {
                          $('wtool_name').setValue('This will be ignored');
                        });
                    }

                  
                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
