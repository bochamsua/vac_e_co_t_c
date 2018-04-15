<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress list block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstprogress_List extends Mage_Core_Block_Template
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
        $kstprogresses = Mage::getResourceModel('bs_kst/kstprogress_collection')
                         ->addFieldToFilter('status', 1);
        $kstprogresses->setOrder('ac_reg', 'asc');
        $this->setKstprogresses($kstprogresses);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_KST_Block_Kstprogress_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_kst.kstprogress.html.pager'
        )
        ->setCollection($this->getKstprogresses());
        $this->setChild('pager', $pager);
        $this->getKstprogresses()->load();
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
