<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop admin edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Workshop_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_controller = 'adminhtml_workshop';
        /*$this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_logistics')->__('Save Workshop')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bs_logistics')->__('Delete Workshop')
        );*/
        $this->_addButton(
            'f32',
            array(
                'label'   => Mage::helper('bs_worksheet')->__('TC-F32'),
                'onclick'   => "setLocation('{$this->getUrl('*/*/generateF32', array('_current'=>true))}')",
                'class'   => 'reset',
            )
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/workshop/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/workshop/delete");

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
        if (Mage::registry('current_workshop') && Mage::registry('current_workshop')->getId()) {
            return Mage::helper('bs_logistics')->__(
                "Edit Workshop '%s'",
                $this->escapeHtml(Mage::registry('current_workshop')->getWorkshopName())
            );
        } else {
            return Mage::helper('bs_logistics')->__('Add Workshop');
        }
    }
}
