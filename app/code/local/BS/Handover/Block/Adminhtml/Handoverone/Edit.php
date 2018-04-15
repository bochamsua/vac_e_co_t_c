<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V1 admin edit form
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Block_Adminhtml_Handoverone_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_handover';
        $this->_controller = 'adminhtml_handoverone';

        $this->_addButton(
            'duplicate',
            array(
                'label'   => Mage::helper('bs_handover')->__('Duplicate'),
                'onclick'   => "duplicateConfirm()",
                'class'   => 'reset',
            )
        );

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_handover')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );

        $this->_updateButton('save','onclick','saveOnly()');
        $this->_updateButton('delete','onclick','deleteOnly()');
        $add = '';
        $popup = $this->getRequest()->getParam('popup');
        $params = array($this->_objectId => $this->getRequest()->getParam($this->_objectId));
        if($popup){
            $add = 'popup/1/';
            $this->_removeButton('saveandcontinue');
            $this->_removeButton('back');
            $this->_addButton(
                'closewindow',
                array(
                    'label'   => Mage::helper('bs_handover')->__('Close'),
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
            function duplicateConfirm() {
                deleteConfirm('". Mage::helper('adminhtml')->__('Are you sure you want to do this?')."','".$this->getUrl('*/*/duplicate', $params) . "');
            }

            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_material/bs_handover/handoverone/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_material/bs_handover/handoverone/delete");

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
        if (Mage::registry('current_handoverone') && Mage::registry('current_handoverone')->getId()) {
            return Mage::helper('bs_handover')->__(
                "Edit Minutes of Handover V1 '%s'",
                $this->escapeHtml(Mage::registry('current_handoverone')->getTitle())
            );
        } else {
            return Mage::helper('bs_handover')->__('Add Minutes of Handover V1');
        }
    }
}
