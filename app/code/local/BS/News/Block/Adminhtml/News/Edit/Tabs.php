<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News admin edit tabs
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Block_Adminhtml_News_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('news_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_news')->__('News'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return BS_News_Block_Adminhtml_News_Edit_Tabs
     * @author Bui Phong
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_news',
            array(
                'label'   => Mage::helper('bs_news')->__('News'),
                'title'   => Mage::helper('bs_news')->__('News'),
                'content' => $this->getLayout()->createBlock(
                    'bs_news/adminhtml_news_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve news entity
     *
     * @access public
     * @return BS_News_Model_News
     * @author Bui Phong
     */
    public function getNews()
    {
        return Mage::registry('current_news');
    }
}
