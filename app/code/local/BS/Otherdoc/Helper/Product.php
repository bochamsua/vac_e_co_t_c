<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Helper_Product extends BS_Otherdoc_Helper_Data
{

    /**
     * get the selected other\'s course documents for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedOtherdocs(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedOtherdocs()) {
            $otherdocs = array();
            foreach ($this->getSelectedOtherdocsCollection($product) as $otherdoc) {
                $otherdocs[] = $otherdoc;
            }
            $product->setSelectedOtherdocs($otherdocs);
        }
        return $product->getData('selected_otherdocs');
    }

    /**
     * get other\'s course document collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Otherdoc_Model_Resource_Otherdoc_Collection
     * @author Bui Phong
     */
    public function getSelectedOtherdocsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_otherdoc/otherdoc_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
