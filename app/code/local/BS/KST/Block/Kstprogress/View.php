<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress view block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstprogress_View extends Mage_Core_Block_Template
{
    /**
     * get the current progress
     *
     * @access public
     * @return mixed (BS_KST_Model_Kstprogress|null)
     * @author Bui Phong
     */
    public function getCurrentKstprogress()
    {
        return Mage::registry('current_kstprogress');
    }
}
