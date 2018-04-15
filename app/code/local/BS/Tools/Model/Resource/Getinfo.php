<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Get Info resource model
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Model_Resource_Getinfo extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_tools/getinfo', 'entity_id');
    }
}
