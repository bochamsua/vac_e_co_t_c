<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject view block
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Subject_View extends Mage_Core_Block_Template
{
    /**
     * get the current subject
     *
     * @access public
     * @return mixed (BS_Bank_Model_Subject|null)
     * @author Bui Phong
     */
    public function getCurrentSubject()
    {
        return Mage::registry('current_subject');
    }
}
