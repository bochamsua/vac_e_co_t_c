<?php
/**
 * BS_InstructorCopy extension
 * 
 * @category       BS
 * @package        BS_InstructorCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Copy resource model
 *
 * @category    BS
 * @package     BS_InstructorCopy
 * @author Bui Phong
 */
class BS_InstructorCopy_Model_Resource_Instructorcopy extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_instructorcopy/instructorcopy', 'entity_id');
    }
}
