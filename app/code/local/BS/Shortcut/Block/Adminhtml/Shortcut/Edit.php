<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Shortcut admin edit form
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Block_Adminhtml_Shortcut_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_shortcut';
        $this->_controller = 'adminhtml_shortcut';

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_shortcut')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );

        $this->_updateButton('save','onclick','saveOnly()');
        $add = '';
        $popup = $this->getRequest()->getParam('popup');
        if($popup){
            $add = 'popup/1/';
        }
        $this->_formScripts[] = "

            function saveOnly() {
                editForm.submit($('edit_form').action+'".$add."');
            }

            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("cms/shortcut/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("cms/shortcut/delete");

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
        if (Mage::registry('current_shortcut') && Mage::registry('current_shortcut')->getId()) {
            return Mage::helper('bs_shortcut')->__(
                "Edit Shortcut '%s'",
                $this->escapeHtml(Mage::registry('current_shortcut')->getShortcut())
            );
        } else {
            return Mage::helper('bs_shortcut')->__('Add Shortcut');
        }
    }
}
