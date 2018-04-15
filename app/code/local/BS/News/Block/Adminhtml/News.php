<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News admin block
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_news';
        $this->_blockGroup         = 'bs_news';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_news')->__('News');
        $this->_updateButton('add', 'label', Mage::helper('bs_news')->__('Add News'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("cms/news/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
