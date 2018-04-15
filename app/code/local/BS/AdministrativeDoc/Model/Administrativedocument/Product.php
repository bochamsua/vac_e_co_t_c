<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document product model
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Model_Administrativedocument_Product extends Mage_Core_Model_Abstract
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
        $this->_init('bs_administrativedoc/administrativedocument_product');
    }

    /**
     * Save data for administrative document-product relation
     * @access public
     * @param  BS_AdministrativeDoc_Model_Administrativedocument $administrativedocument
     * @return BS_AdministrativeDoc_Model_Administrativedocument_Product
     * @author Bui Phong
     */
    public function saveAdministrativedocumentRelation($administrativedocument)
    {
        $data = $administrativedocument->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveAdministrativedocumentRelation($administrativedocument, $data);
        }
        return $this;
    }

    /**
     * get products for administrative document
     *
     * @access public
     * @param BS_AdministrativeDoc_Model_Administrativedocument $administrativedocument
     * @return BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($administrativedocument)
    {
        $collection = Mage::getResourceModel('bs_administrativedoc/administrativedocument_product_collection')
            ->addAdministrativedocumentFilter($administrativedocument);
        return $collection;
    }
}
