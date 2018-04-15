<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum product model
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Curriculum_Product extends Mage_Core_Model_Abstract
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
        $this->_init('bs_traininglist/curriculum_product');
    }

    /**
     * Save data for training curriculum-product relation
     * @access public
     * @param  BS_Traininglist_Model_Curriculum $curriculum
     * @return BS_Traininglist_Model_Curriculum_Product
     * @author Bui Phong
     */
    public function saveCurriculumRelation($curriculum)
    {
        $data = $curriculum->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveCurriculumRelation($curriculum, $data);
        }
        return $this;
    }

    /**
     * get products for training curriculum
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum $curriculum
     * @return BS_Traininglist_Model_Resource_Curriculum_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($curriculum)
    {
        $collection = Mage::getResourceModel('bs_traininglist/curriculum_product_collection')
            ->addCurriculumFilter($curriculum);
        return $collection;
    }
}
