<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Items list block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstsubject_Kstitem_List extends BS_KST_Block_Kstitem_List
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
        $kstsubject = $this->getKstsubject();
        if ($kstsubject) {
            $this->getKstitems()->addFieldToFilter('kstsubject_id', $kstsubject->getId());
        }
    }

    /**
     * prepare the layout - actually do nothing
     *
     * @access protected
     * @return BS_KST_Block_Kstsubject_Kstitem_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current subject
     *
     * @access public
     * @return BS_KST_Model_Kstsubject
     * @author Bui Phong
     */
    public function getKstsubject()
    {
        return Mage::registry('current_kstsubject');
    }
}
