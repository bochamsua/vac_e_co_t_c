<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * CAR Document edit form tab
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Block_Adminhtml_Cardoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Car_Block_Adminhtml_Cardoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('cardoc_');
        $form->setFieldNameSuffix('cardoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'cardoc_form',
            array('legend' => Mage::helper('bs_car')->__('CAR Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_car/adminhtml_cardoc_helper_file')
        );
        $values = Mage::getResourceModel('bs_car/qacar_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="cardoc_qacar_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeQacarIdLink() {
                if ($(\'cardoc_qacar_id\').value == \'\') {
                    $(\'cardoc_qacar_id_link\').hide();
                } else {
                    $(\'cardoc_qacar_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/car_qacar/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'cardoc_qacar_id\').value);
                    $(\'cardoc_qacar_id_link\').href = realUrl;
                    $(\'cardoc_qacar_id_link\').innerHTML = text.replace(\'{#name}\', $(\'cardoc_qacar_id\').options[$(\'cardoc_qacar_id\').selectedIndex].innerHTML);
                }
            }
            $(\'cardoc_qacar_id\').observe(\'change\', changeQacarIdLink);
            changeQacarIdLink();
            </script>';

        $fieldset->addField(
            'qacar_id',
            'select',
            array(
                'label'     => Mage::helper('bs_car')->__('QA Car'),
                'name'      => 'qacar_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'doc_name',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Name'),
                'name'  => 'doc_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'doc_date',
            'date',
            array(
                'label' => Mage::helper('bs_car')->__('Date'),
                'name'  => 'doc_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'doc_file',
            'file',
            array(
                'label' => Mage::helper('bs_car')->__('File'),
                'name'  => 'doc_file',

           )
        );

        $fieldset->addField(
            'doc_note',
            'text',
            array(
                'label' => Mage::helper('bs_car')->__('Note'),
                'name'  => 'doc_note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_car')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_car')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_car')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_cardoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCardocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCardocData());
            Mage::getSingleton('adminhtml/session')->setCardocData(null);
        } elseif (Mage::registry('current_cardoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_cardoc')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
