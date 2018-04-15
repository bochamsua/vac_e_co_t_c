<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Cabinet admin edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Filecabinet_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'bs_logistics';
        $this->_controller = 'adminhtml_filecabinet';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_logistics')->__('Save')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bs_logistics')->__('Delete')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_logistics')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/filecabinet/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/filecabinet/delete");

        if(!$isAllowedEdit){
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
        }
        if(!$isAllowedDelete){
            $this->_removeButton('delete');
        }
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_filecabinet') && Mage::registry('current_filecabinet')->getId()) {
            return Mage::helper('bs_logistics')->__(
                "Edit File Cabinet '%s'",
                $this->escapeHtml(Mage::registry('current_filecabinet')->getFilecabinetName())
            );
        } else {
            return Mage::helper('bs_logistics')->__('Add File Cabinet');
        }
    }
}
