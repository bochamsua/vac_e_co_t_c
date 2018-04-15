<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee resource model
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
class BS_ImportTrainee_Model_Resource_Importtrainee extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_importtrainee/importtrainee', 'entity_id');
    }
}
