<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * User resource model
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Model_Resource_User extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_news/user', 'entity_id');
    }
}
