<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject resource model
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Model_Resource_Subject extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_bank/subject', 'entity_id');
    }
}
