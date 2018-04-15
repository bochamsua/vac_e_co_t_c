<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Helper_Product extends BS_Instructor_Helper_Data
{

    /**
     * get the selected instructors for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedInstructors(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedInstructors()) {
            $instructors = array();
            foreach ($this->getSelectedInstructorsCollection($product) as $instructor) {
                $instructors[] = $instructor;
            }
            $product->setSelectedInstructors($instructors);
        }
        return $product->getData('selected_instructors');
    }

    /**
     * get instructor collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Instructor_Model_Resource_Instructor_Collection
     * @author Bui Phong
     */
    public function getSelectedInstructorsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_instructor/instructor_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
