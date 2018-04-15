<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire admin edit form
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Block_Adminhtml_Questionnaire_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_questionnaire';
        $this->_controller = 'adminhtml_questionnaire';

        /*$this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_questionnaire')->__('Generate And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );*/

        $this->_updateButton('save','onclick','saveOnly()');
        $this->_updateButton('save','label','Generate');
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
                    'label'   => Mage::helper('bs_questionnaire')->__('Close'),
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/questionnaire/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/questionnaire/delete");

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
        if (Mage::registry('current_questionnaire') && Mage::registry('current_questionnaire')->getId()) {
            return Mage::helper('bs_questionnaire')->__(
                "Edit Questionnaire '%s'",
                $this->escapeHtml(Mage::registry('current_questionnaire')->getName())
            );
        } else {
            return Mage::helper('bs_questionnaire')->__('Add Questionnaire');
        }
    }
}
