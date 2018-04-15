<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Helper_Product extends BS_Logistics_Helper_Data
{

    /**
     * get the selected classroom/examrooms for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedClassrooms(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedClassrooms()) {
            $classrooms = array();
            foreach ($this->getSelectedClassroomsCollection($product) as $classroom) {
                $classrooms[] = $classroom;
            }
            $product->setSelectedClassrooms($classrooms);
        }
        return $product->getData('selected_classrooms');
    }

    /**
     * get classroom/examroom collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Logistics_Model_Resource_Classroom_Collection
     * @author Bui Phong
     */
    public function getSelectedClassroomsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_logistics/classroom_collection')
            ->addProductFilter($product);
        return $collection;
    }

    /**
     * get the selected workshops for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedWorkshops(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedWorkshops()) {
            $workshops = array();
            foreach ($this->getSelectedWorkshopsCollection($product) as $workshop) {
                $workshops[] = $workshop;
            }
            $product->setSelectedWorkshops($workshops);
        }
        return $product->getData('selected_workshops');
    }

    /**
     * get workshop collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Logistics_Model_Resource_Workshop_Collection
     * @author Bui Phong
     */
    public function getSelectedWorkshopsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_logistics/workshop_collection')
            ->addProductFilter($product);
        return $collection;
    }

    /**
     * get the selected file folders for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedFilefolders(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedFilefolders()) {
            $filefolders = array();
            foreach ($this->getSelectedFilefoldersCollection($product) as $filefolder) {
                $filefolders[] = $filefolder;
            }
            $product->setSelectedFilefolders($filefolders);
        }
        return $product->getData('selected_filefolders');
    }

    /**
     * get file folder collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Logistics_Model_Resource_Filefolder_Collection
     * @author Bui Phong
     */
    public function getSelectedFilefoldersCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_logistics/filefolder_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
