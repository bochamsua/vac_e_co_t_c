<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item list block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstitem_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $kstitems = Mage::getResourceModel('bs_kst/kstitem_collection')
                         ->addFieldToFilter('status', 1);
        $kstitems->setOrder('name', 'asc');
        $this->setKstitems($kstitems);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_KST_Block_Kstitem_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_kst.kstitem.html.pager'
        )
        ->setCollection($this->getKstitems());
        $this->setChild('pager', $pager);
        $this->getKstitems()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
