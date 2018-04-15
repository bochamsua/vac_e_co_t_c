<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Helper_Product extends BS_Traininglist_Helper_Data
{

    /**
     * get the selected training curriculum for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedCurriculums(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedCurriculums()) {
            $curriculums = array();
            foreach ($this->getSelectedCurriculumsCollection($product) as $curriculum) {
                $curriculums[] = $curriculum;
            }
            $product->setSelectedCurriculums($curriculums);
        }
        return $product->getData('selected_curriculums');
    }

    /**
     * get training curriculum collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Traininglist_Model_Resource_Curriculum_Collection
     * @author Bui Phong
     */
    public function getSelectedCurriculumsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_traininglist/curriculum_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
