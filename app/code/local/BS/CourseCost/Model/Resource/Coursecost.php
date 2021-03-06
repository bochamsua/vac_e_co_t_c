<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost resource model
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Model_Resource_Coursecost extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_coursecost/coursecost', 'entity_id');
    }
}
