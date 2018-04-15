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
class BS_News_Block_Adminhtml_News_View extends Mage_Adminhtml_Block_Widget_View_Container
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
        $this->removeButton('edit');
        $this->updateButton('back', 'onclick', 'window.location.href=\'' . $this->getUrl('*/') . '\'');
        $this->_addButton(
            'read',
            array(
                'label'   => Mage::helper('bs_news')->__('Mark as read'),
                'onclick' => 'window.location.href=\'' . $this->getUrl('*/*/read', array('id'=>$this->getRequest()->getParam('id'))) . '\'',
                'class'   => 'save',
            ),
            -100
        );

    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
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
        $for    = $this->getRequest()->getParam('for');

        return Mage::helper('bs_news')->__('Notification for: %s');

    }
}
