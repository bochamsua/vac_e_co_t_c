<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content resource model
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Model_Resource_Subjectcontent extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_subject/subjectcontent', 'entity_id');
    }
}
