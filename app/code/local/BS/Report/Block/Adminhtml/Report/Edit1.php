<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report admin edit form
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Report_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_report';
        $this->_controller = 'adminhtml_report';

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_report')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );

        $this->_addButton(
            'saverate',
            array(
                'label'   => Mage::helper('bs_report')->__('Save Rating'),
                'onclick' => 'saveRating()',
                'class'   => 'save',
            ),
            -100
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
                    'label'   => Mage::helper('bs_report')->__('Close'),
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

            function saveRating() {
                editForm.submit($('edit_form').action+'backto/manage/');
            }
        ";

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_report/report/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_report/report/delete");

        $currentUser = Mage::getSingleton('admin/session')->getUser();
        $currentId = $currentUser->getId();

        $currentReportId = Mage::registry('current_report')->getUserId();

        $isallowSame = true;
        if($currentId != $currentReportId){
            if(Mage::registry('current_report')->getId()){
                $isallowSame = false;
            }

        }

        if(!$isAllowedEdit || !$isallowSame){
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
        }
        if(!$isAllowedDelete || !$isallowSame){
            $this->_removeButton('delete');
        }

        $check = $this->helper('bs_report')->checkSupervisor();
        if(!$check){
            $this->_removeButton('saverate');
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
        if (Mage::registry('current_report') && Mage::registry('current_report')->getId()) {
            return Mage::helper('bs_report')->__(
                "Edit Individual Report '%s'",
                $this->escapeHtml(Mage::registry('current_report')->getBrief())
            );
        } else {
            return Mage::helper('bs_report')->__('Add Report');
        }
    }
}
