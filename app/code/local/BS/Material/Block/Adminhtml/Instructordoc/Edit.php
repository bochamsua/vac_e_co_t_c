<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document admin edit form
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Block_Adminhtml_Instructordoc_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_material';
        $this->_controller = 'adminhtml_instructordoc';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('bs_material')->__('Save')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('bs_material')->__('Delete')
        );


        $this->_updateButton('save','onclick','saveOnly()');
        $this->_updateButton('delete','onclick','deleteOnly()');
        $add = '';
        $popup = $this->getRequest()->getParam('popup');
        if($popup){
            $add = 'popup/1/';
            $this->_removeButton('saveandcontinue');
            $this->_removeButton('back');
            $this->_addButton(
                'closewindow',
                array(
                    'label'   => Mage::helper('bs_material')->__('Close'),
                    'onclick' => 'window.close()',
                    'class'   => 'back',
                ),
                -1
            );
        }
        $this->_formScripts[] = "

            function deleteOnly() {
                deleteConfirm('". Mage::helper('adminhtml')->__('Are you sure you want to do this?')."','".$this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'popup'=>1)) . "');
            }
            function saveOnly() {
                editForm.submit($('edit_form').action+'".$add."');
            }

            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/instructordoc/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/instructordoc/delete");

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
        if (Mage::registry('current_instructordoc') && Mage::registry('current_instructordoc')->getId()) {
            return Mage::helper('bs_material')->__(
                "Edit Instructor Document '%s'",
                $this->escapeHtml(Mage::registry('current_instructordoc')->getIdocName())
            );
        } else {
            return Mage::helper('bs_material')->__('Add Instructor Document');
        }
    }
}
