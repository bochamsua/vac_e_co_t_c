<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Copy admin edit form
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
class BS_SubjectCopy_Block_Adminhtml_Subjectcopy_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_subjectcopy';
        $this->_controller = 'adminhtml_subjectcopy';

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_subjectcopy')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
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
                    'label'   => Mage::helper('bs_subjectcopy')->__('Close'),
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcopy/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcopy/delete");

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
        if (Mage::registry('current_subjectcopy') && Mage::registry('current_subjectcopy')->getId()) {
            return Mage::helper('bs_subjectcopy')->__(
                "Edit Subject Copy '%s'",
                $this->escapeHtml(Mage::registry('current_subjectcopy')->getCFrom())
            );
        } else {
            return Mage::helper('bs_subjectcopy')->__('Add Subject Copy');
        }
    }
}
