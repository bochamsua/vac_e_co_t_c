<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Instructor Feedback resource model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Resource_Ifeedback extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_kst/ifeedback', 'entity_id');
    }
}
