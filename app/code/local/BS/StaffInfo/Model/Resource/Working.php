<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Working resource model
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Model_Resource_Working extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_staffinfo/working', 'entity_id');
    }
}
