<?php
/**
 * BS_TraineeCert extension
 * 
 * @category       BS
 * @package        BS_TraineeCert
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Certificate resource model
 *
 * @category    BS
 * @package     BS_TraineeCert
 * @author Bui Phong
 */
class BS_TraineeCert_Model_Resource_Traineecert extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_traineecert/traineecert', 'entity_id');
    }
}
