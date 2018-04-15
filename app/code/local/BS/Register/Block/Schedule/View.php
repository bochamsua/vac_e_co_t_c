<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule view block
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Schedule_View extends Mage_Core_Block_Template
{
    /**
     * get the current course schedule
     *
     * @access public
     * @return mixed (BS_Register_Model_Schedule|null)
     * @author Bui Phong
     */
    public function getCurrentSchedule()
    {
        return Mage::registry('current_schedule');
    }
}
