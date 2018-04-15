<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V2 resource model
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Model_Resource_Handovertwo extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_handover/handovertwo', 'entity_id');
    }
}
