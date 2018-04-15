<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject list block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstsubject_List extends Mage_Core_Block_Template
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
        $kstsubjects = Mage::getResourceModel('bs_kst/kstsubject_collection')
                         ->addFieldToFilter('status', 1);
        $kstsubjects->setOrder('name', 'asc');
        $this->setKstsubjects($kstsubjects);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_KST_Block_Kstsubject_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_kst.kstsubject.html.pager'
        )
        ->setCollection($this->getKstsubjects());
        $this->setChild('pager', $pager);
        $this->getKstsubjects()->load();
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
