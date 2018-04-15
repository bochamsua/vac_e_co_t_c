<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document resource model
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Resource_Instructordoc extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_material/instructordoc', 'entity_id');
    }
}
