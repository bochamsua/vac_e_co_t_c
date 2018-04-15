<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer admin edit form
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Answer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_bank';
        $this->_controller = 'adminhtml_answer';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_bank')->__('Save')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bs_bank')->__('Delete')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_bank')->__('Save And Continue Edit'),
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_bank/answer/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_bank/answer/delete");

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
        if (Mage::registry('current_answer') && Mage::registry('current_answer')->getId()) {
            return Mage::helper('bs_bank')->__(
                "Edit Answer '%s'",
                $this->escapeHtml(Mage::registry('current_answer')->getQuestionId())
            );
        } else {
            return Mage::helper('bs_bank')->__('Add Answer');
        }
    }
}
