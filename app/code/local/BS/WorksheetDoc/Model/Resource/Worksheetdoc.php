<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document resource model
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Resource_Worksheetdoc extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_worksheetdoc/worksheetdoc', 'entity_id');
    }
}
