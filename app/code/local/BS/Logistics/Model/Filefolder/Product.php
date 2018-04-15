<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder product model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Filefolder_Product extends Mage_Core_Model_Abstract
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
        $this->_init('bs_logistics/filefolder_product');
    }

    /**
     * Save data for file folder-product relation
     * @access public
     * @param  BS_Logistics_Model_Filefolder $filefolder
     * @return BS_Logistics_Model_Filefolder_Product
     * @author Bui Phong
     */
    public function saveFilefolderRelation($filefolder)
    {
        $data = $filefolder->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveFilefolderRelation($filefolder, $data);
        }
        return $this;
    }

    /**
     * get products for file folder
     *
     * @access public
     * @param BS_Logistics_Model_Filefolder $filefolder
     * @return BS_Logistics_Model_Resource_Filefolder_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($filefolder)
    {
        $collection = Mage::getResourceModel('bs_logistics/filefolder_product_collection')
            ->addFilefolderFilter($filefolder);
        return $collection;
    }
}
