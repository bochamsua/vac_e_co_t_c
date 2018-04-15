<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News admin edit form
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Block_Adminhtml_News_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'bs_news';
        $this->_controller = 'adminhtml_news';

        $createdUser = null;
        $currentNews = Mage::registry('current_news');
        if($currentNews->getId()){
            $createdUser = $currentNews->getUserId();
        }
        $currentUser = Mage::getSingleton('admin/session')->getUser()->getId();
        $owner = true;
        if($createdUser && $createdUser != $currentUser && $currentUser != 1){
            $owner = false;
        }

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('bs_news')->__('Save And Continue Edit'),
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
                    'label'   => Mage::helper('bs_news')->__('Close'),
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

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("cms/news/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("cms/news/delete");

        if(!$isAllowedEdit || !$owner){
            $this->_removeButton('save');
            $this->_removeButton('saveandcontinue');
        }
        if(!$isAllowedDelete || !$owner){
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
        if (Mage::registry('current_news') && Mage::registry('current_news')->getId()) {
            return Mage::helper('bs_news')->__(
                "Edit News '%s'",
                $this->escapeHtml(Mage::registry('current_news')->getTitle())
            );
        } else {
            return Mage::helper('bs_news')->__('Add News');
        }
    }
}
