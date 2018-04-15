<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Helper_Product extends BS_AdministrativeDoc_Helper_Data
{

    /**
     * get the selected administrative documents for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedAdministrativedocuments(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedAdministrativedocuments()) {
            $administrativedocuments = array();
            foreach ($this->getSelectedAdministrativedocumentsCollection($product) as $administrativedocument) {
                $administrativedocuments[] = $administrativedocument;
            }
            $product->setSelectedAdministrativedocuments($administrativedocuments);
        }
        return $product->getData('selected_administrativedocuments');
    }

    /**
     * get administrative document collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_AdministrativeDoc_Model_Resource_Administrativedocument_Collection
     * @author Bui Phong
     */
    public function getSelectedAdministrativedocumentsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_administrativedoc/administrativedocument_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
