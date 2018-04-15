<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Workshop product model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Workshop_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->_init('bs_logistics/workshop_product');
    }

    /**
     * Save data for workshop-product relation
     * @access public
     * @param  BS_Logistics_Model_Workshop $workshop
     * @return BS_Logistics_Model_Workshop_Product
     * @author Bui Phong
     */
    public function saveWorkshopRelation($workshop)
    {
        $data = $workshop->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveWorkshopRelation($workshop, $data);
        }
        return $this;
    }

    /**
     * get products for workshop
     *
     * @access public
     * @param BS_Logistics_Model_Workshop $workshop
     * @return BS_Logistics_Model_Resource_Workshop_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($workshop)
    {
        $collection = Mage::getResourceModel('bs_logistics/workshop_product_collection')
            ->addWorkshopFilter($workshop);
        return $collection;
    }
}
