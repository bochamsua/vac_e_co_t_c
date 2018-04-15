<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject view block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Kstsubject_View extends Mage_Core_Block_Template
{
    /**
     * get the current subject
     *
     * @access public
     * @return mixed (BS_KST_Model_Kstsubject|null)
     * @author Bui Phong
     */
    public function getCurrentKstsubject()
    {
        return Mage::registry('current_kstsubject');
    }
}
