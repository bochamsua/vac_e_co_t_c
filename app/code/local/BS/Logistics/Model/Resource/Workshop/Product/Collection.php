<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Resource_Workshop_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return BS_Logistics_Model_Resource_Workshop_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_logistics/workshop_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add workshop filter
     *
     * @access public
     * @param BS_Logistics_Model_Workshop | int $workshop
     * @return BS_Logistics_Model_Resource_Workshop_Product_Collection
     * @author Bui Phong
     */
    public function addWorkshopFilter($workshop)
    {
        if ($workshop instanceof BS_Logistics_Model_Workshop) {
            $workshop = $workshop->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.workshop_id = ?', $workshop);
        return $this;
    }
}
