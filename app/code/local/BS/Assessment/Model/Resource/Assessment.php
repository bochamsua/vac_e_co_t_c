<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment resource model
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Model_Resource_Assessment extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_assessment/assessment', 'entity_id');
    }
}
