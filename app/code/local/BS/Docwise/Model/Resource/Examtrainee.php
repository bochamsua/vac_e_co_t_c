<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Trainee resource model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Resource_Examtrainee extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_docwise/examtrainee', 'entity_id');
    }
}
