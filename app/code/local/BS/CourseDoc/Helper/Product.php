<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Helper_Product extends BS_CourseDoc_Helper_Data
{

    /**
     * get the selected course docs for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedCoursedocs(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedCoursedocs()) {
            $coursedocs = array();
            foreach ($this->getSelectedCoursedocsCollection($product) as $coursedoc) {
                $coursedocs[] = $coursedoc;
            }
            $product->setSelectedCoursedocs($coursedocs);
        }
        return $product->getData('selected_coursedocs');
    }

    /**
     * get course doc collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_CourseDoc_Model_Resource_Coursedoc_Collection
     * @author Bui Phong
     */
    public function getSelectedCoursedocsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_coursedoc/coursedoc_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
