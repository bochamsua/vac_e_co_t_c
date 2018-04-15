<?php
/**
 * BS_Staff extension
 * 
 * @category       BS
 * @package        BS_Staff
 * @copyright      Copyright (c) 2015
 */
/**
 * Staff resource model
 *
 * @category    BS
 * @package     BS_Staff
 * @author Bui Phong
 */
class BS_Staff_Model_Resource_Staff extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_staff/staff', 'entity_id');
    }
}
