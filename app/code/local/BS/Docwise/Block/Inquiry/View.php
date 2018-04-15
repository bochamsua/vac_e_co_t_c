<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Inquiry view block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Inquiry_View extends Mage_Core_Block_Template
{
    /**
     * get the current inquiry
     *
     * @access public
     * @return mixed (BS_Docwise_Model_Inquiry|null)
     * @author Bui Phong
     */
    public function getCurrentInquiry()
    {
        return Mage::registry('current_inquiry');
    }
}
