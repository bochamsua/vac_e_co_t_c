<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee admin edit form
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Trainee_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_docwise';
        $this->_controller = 'adminhtml_trainee';

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );

        $this->_addButton(
            'addcore',
            array(
                'label'   => Mage::helper('bs_docwise')->__('Add Score'),
                'onclick'   => 'setLocation(\''. $this->getUrl('*/docwise_score/new', array('trainee_id'=>Mage::registry('current_trainee')->getId())).'\')',
                'class'   => 'reset',
            )
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
                    'label'   => Mage::helper('bs_docwise')->__('Close'),
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/trainee/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/trainee/delete");

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
        if (Mage::registry('current_trainee') && Mage::registry('current_trainee')->getId()) {
            return Mage::helper('bs_docwise')->__(
                "Edit Trainee '%s'",
                $this->escapeHtml(Mage::registry('current_trainee')->getTraineeName())
            );
        } else {
            return Mage::helper('bs_docwise')->__('Add Trainee');
        }
    }
}
