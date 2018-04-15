<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet admin edit form
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Worksheet_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_worksheet';
        $this->_controller = 'adminhtml_worksheet';
        $this->_addButton(
            'generate',
            array(
                'label'   => Mage::helper('bs_worksheet')->__('Generate 8016'),
                'onclick'   => "generateConfirm()",
                'class'   => 'reset',
            )
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_worksheet')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $params = array($this->_objectId => $this->getRequest()->getParam($this->_objectId));

        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
            function generateConfirm() {
                deleteConfirm('". Mage::helper('adminhtml')->__('Are you sure you want to do this?')."','".$this->getUrl('*/*/generateSixteen', $params) . "');
            }



        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/worksheet/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/worksheet/delete");

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
        if (Mage::registry('current_worksheet') && Mage::registry('current_worksheet')->getId()) {
            return Mage::helper('bs_worksheet')->__(
                "Edit Worksheet '%s'",
                $this->escapeHtml(Mage::registry('current_worksheet')->getWsName())
            );
        } else {
            return Mage::helper('bs_worksheet')->__('Add Worksheet');
        }
    }
}
