<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Folder Content edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Foldercontent_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Foldercontent_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('foldercontent_');
        $form->setFieldNameSuffix('foldercontent');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'foldercontent_form',
            array('legend' => Mage::helper('bs_logistics')->__('Folder Content'))
        );

        $currentfContent = Mage::registry('current_foldercontent');
        $fId = null;
        if($this->getRequest()->getParam('filefolder_id')){
            $fId = $this->getRequest()->getParam('filefolder_id');
        }elseif ($currentfContent){
            $fId = $currentfContent->getFilefolderId();
        }


        $values = Mage::getResourceModel('bs_logistics/filefolder_collection');
        if($fId){
            $values->addFieldToFilter('entity_id', $fId);
        }
        $values = $values->toOptionArray();




        $fieldset->addField(
            'filefolder_id',
            'select',
            array(
                'label' => Mage::helper('bs_logistics')->__('Filefolder'),
                'name'  => 'filefolder_id',
                'values'    => $values,

            )
        );


        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Title'),
                'name'  => 'title',

           )
        );

        $fieldset->addField(
            'description',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'updated_date',
            'date',
            array(
                'label' => Mage::helper('bs_logistics')->__('Updated Date'),
                'name'  => 'updated_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );



        $fieldset->addField(
            'position',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Position'),
                'name'  => 'position',

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
        $formValues = Mage::registry('current_foldercontent')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getFoldercontentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFoldercontentData());
            Mage::getSingleton('adminhtml/session')->setFoldercontentData(null);
        } elseif (Mage::registry('current_foldercontent')) {
            $formValues = array_merge($formValues, Mage::registry('current_foldercontent')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
