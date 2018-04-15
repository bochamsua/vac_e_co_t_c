<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family resource model
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Model_Resource_Family extends Mage_Catalog_Model_Resource_Abstract
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
        $this->setType('bs_tc_family')
            ->setConnection(
                $resource->getConnection('family_read'),
                $resource->getConnection('family_write')
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
