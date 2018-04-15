<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group Item edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wgroupitem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wgroupitem_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('wgroupitem_');
        $form->setFieldNameSuffix('wgroupitem');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'wgroupitem_form',
            array('legend' => Mage::helper('bs_logistics')->__('Equipment'))
        );
        $values = Mage::getResourceModel('bs_logistics/workshop_collection')
            ->toOptionArray();
        //array_unshift($values, array('label' => '', 'value' => ''));

        /*$html = '<a href="{#url}" id="wgroupitem_workshop_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeWorkshopIdLink() {
                if ($(\'wgroupitem_workshop_id\').value == \'\') {
                    $(\'wgroupitem_workshop_id_link\').hide();
                } else {
                    $(\'wgroupitem_workshop_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_workshop/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wgroupitem_workshop_id\').value);
                    $(\'wgroupitem_workshop_id_link\').href = realUrl;
                    $(\'wgroupitem_workshop_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wgroupitem_workshop_id\').options[$(\'wgroupitem_workshop_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wgroupitem_workshop_id\').observe(\'change\', changeWorkshopIdLink);
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
            ->toOptionArray();
        //array_unshift($values, array('label' => '', 'value' => ''));

        /*$html = '<a href="{#url}" id="wgroupitem_grouptype_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeGrouptypeIdLink() {
                if ($(\'wgroupitem_grouptype_id\').value == \'\') {
                    $(\'wgroupitem_grouptype_id_link\').hide();
                } else {
                    $(\'wgroupitem_grouptype_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/logistics_grouptype/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'wgroupitem_grouptype_id\').value);
                    $(\'wgroupitem_grouptype_id_link\').href = realUrl;
                    $(\'wgroupitem_grouptype_id_link\').innerHTML = text.replace(\'{#name}\', $(\'wgroupitem_grouptype_id\').options[$(\'wgroupitem_grouptype_id\').selectedIndex].innerHTML);
                }
            }
            $(\'wgroupitem_grouptype_id\').observe(\'change\', changeGrouptypeIdLink);
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

        /*$fieldset->addField(
            'qty',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Qty'),
                'name'  => 'qty',

           )
        );*/

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
                'note'  => Mage::helper('bs_logistics')->__('Put each line in any of following format: <br> 1. Name<br>2. Name -- Vietnamese Name'),

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
        $formValues = Mage::registry('current_wgroupitem')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWgroupitemData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWgroupitemData());
            Mage::getSingleton('adminhtml/session')->setWgroupitemData(null);
        } elseif (Mage::registry('current_wgroupitem')) {
            $formValues = array_merge($formValues, Mage::registry('current_wgroupitem')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('wgroupitem_import') != undefined){
                        $('wgroupitem_import').observe('keyup', function() {
                          $('wgroupitem_name').setValue('This will be ignored');
                        });
                    }

                  
                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
