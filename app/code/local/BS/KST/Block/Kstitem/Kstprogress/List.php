<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item Progresses list block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstitem_Kstprogress_List extends BS_KST_Block_Kstprogress_List
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
        $kstitem = $this->getKstitem();
        if ($kstitem) {
            $this->getKstprogresses()->addFieldToFilter('kstitem_id', $kstitem->getId());
        }
    }

    /**
     * prepare the layout - actually do nothing
     *
     * @access protected
     * @return BS_KST_Block_Kstitem_Kstprogress_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current item
     *
     * @access public
     * @return BS_KST_Model_Kstitem
     * @author Bui Phong
     */
    public function getKstitem()
    {
        return Mage::registry('current_kstitem');
    }
}
