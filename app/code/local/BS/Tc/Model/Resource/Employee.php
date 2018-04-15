<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Employee resource model
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Model_Resource_Employee extends Mage_Catalog_Model_Resource_Abstract
{


    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('bs_tc_employee')
            ->setConnection(
                $resource->getConnection('employee_read'),
                $resource->getConnection('employee_write')
            );

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }
}
