<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Product helper
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Helper_Product extends BS_Trainee_Helper_Data
{

    /**
     * get the selected trainees for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedTrainees(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedTrainees()) {
            $trainees = array();
            foreach ($this->getSelectedTraineesCollection($product) as $trainee) {
                $trainees[] = $trainee;
            }
            $product->setSelectedTrainees($trainees);
        }
        return $product->getData('selected_trainees');
    }

    /**
     * get trainee collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return BS_Trainee_Model_Resource_Trainee_Collection
     * @author Bui Phong
     */
    public function getSelectedTraineesCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('bs_trainee/trainee_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
