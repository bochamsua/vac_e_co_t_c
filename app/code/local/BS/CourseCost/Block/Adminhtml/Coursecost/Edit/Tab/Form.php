<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost edit form tab
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Coursecost_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout()
    {
        if($this->getRequest()->getParam('v2', false)){
            $this->setTemplate('bs_coursecost/form.phtml');
        }

        return parent::_prepareLayout(); // TODO: Change the autogenerated stub
    }
    public function getFieldId(){
        return 'cost_row';
    }

    public function getAddButtonId(){
        return 'add_new_row';
    }

    public function getCourseId(){
        return Mage::app()->getRequest()->getParam('product_id');
    }
    public function getGroupSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_select_{{id}}',
                'class' => 'select select-group'
            ))
            ->setName('coursecost[{{id}}][costgroup_id]')
            ->setOptions(Mage::getResourceModel('bs_coursecost/costgroup_collection')->toOptionArray());

        return $select->getHtml();
    }

    public function getItemSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_select_item_{{id}}',
                'class' => 'select select-item'
            ))
            ->setName('coursecost[{{id}}][costitem_id]')
            ->setOptions(Mage::getResourceModel('bs_coursecost/costitem_collection')->toOptionArray());

        return $select->getHtml();
    }
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('coursecost_');
        $form->setFieldNameSuffix('coursecost');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'coursecost_form',
            array('legend' => Mage::helper('bs_coursecost')->__('Course Cost'))
        );

        $productId = $this->getRequest()->getParam('product_id');

        $values = Mage::getResourceModel('bs_coursecost/costgroup_collection')
            ->toOptionArray();




        $fieldset->addField(
            'costgroup_id',
            'select',
            array(
                'label'     => Mage::helper('bs_coursecost')->__('Group'),
                'name'      => 'costgroup_id',
                'required'  => false,
                'values'    => $values,
            )
        );
        $values = Mage::getResourceModel('bs_coursecost/costitem_collection')
            ->toOptionArray();




        $fieldset->addField(
            'costitem_id',
            'select',
            array(
                'label'     => Mage::helper('bs_coursecost')->__('Item'),
                'name'      => 'costitem_id',
                'required'  => false,
                'values'    => $values,
            )
        );

        $fieldset->addField(
            'course_id',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Course'),
                'name'  => 'course_id',

           )
        );

        $fieldset->addField(
            'qty',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Qty'),
                'name'  => 'qty',

           )
        );

        $fieldset->addField(
            'coursecost_cost',
            'text',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Total Cost'),
                'name'  => 'coursecost_cost',
                'note'  => 'Put total cost here for item with custom cost'

            )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_coursecost')->__('Note'),
                'name'  => 'note',

           )
        );
        /*$fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_coursecost')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_coursecost')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_coursecost')->__('Disabled'),
                    ),
                ),
            )
        );*/
        $formValues = Mage::registry('current_coursecost')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCoursecostData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCoursecostData());
            Mage::getSingleton('adminhtml/session')->setCoursecostData(null);
        } elseif (Mage::registry('current_coursecost')) {
            $formValues = array_merge($formValues, Mage::registry('current_coursecost')->getData());
        }

        $formValues = array_merge($formValues, array('course_id' => $productId));

        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('coursecost_course_id') != undefined){
                        $('coursecost_course_id').up('tr').setStyle({'display':'none'});
                    }

                  
                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
