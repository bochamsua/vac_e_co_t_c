<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Type resource model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Resource_Grouptype extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_logistics/grouptype', 'entity_id');
    }
}
