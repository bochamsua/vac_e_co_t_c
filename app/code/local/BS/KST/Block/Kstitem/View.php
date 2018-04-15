<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item view block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstitem_View extends Mage_Core_Block_Template
{
    /**
     * get the current item
     *
     * @access public
     * @return mixed (BS_KST_Model_Kstitem|null)
     * @author Bui Phong
     */
    public function getCurrentKstitem()
    {
        return Mage::registry('current_kstitem');
    }
}
