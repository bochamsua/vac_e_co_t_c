<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Category helper
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Helper_Category extends BS_Traininglist_Helper_Data
{

    /**
     * get the selected training curriculum for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedCurriculums(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedCurriculums()) {
            $curriculums = array();
            foreach ($this->getSelectedCurriculumsCollection($category) as $curriculum) {
                $curriculums[] = $curriculum;
            }
            $category->setSelectedCurriculums($curriculums);
        }
        return $category->getData('selected_curriculums');
    }

    /**
     * get training curriculum collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return BS_Traininglist_Model_Resource_Curriculum_Collection
     * @author Bui Phong
     */
    public function getSelectedCurriculumsCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('bs_traininglist/curriculum_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
