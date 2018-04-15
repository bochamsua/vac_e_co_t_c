<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom admin edit form
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Classroom_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_controller = 'adminhtml_classroom';
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/classroom/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/classroom/delete");

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
        if (Mage::registry('current_classroom') && Mage::registry('current_classroom')->getId()) {
            return Mage::helper('bs_logistics')->__(
                "Edit Classroom/Examroom '%s'",
                $this->escapeHtml(Mage::registry('current_classroom')->getClassroomName())
            );
        } else {
            return Mage::helper('bs_logistics')->__('Add Classroom/Examroom');
        }
    }
}
