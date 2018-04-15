<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum category model
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Curriculum_Category extends Mage_Core_Model_Abstract
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
        $this->_init('bs_traininglist/curriculum_category');
    }

    /**
     * Save data for training curriculum-category relation
     *
     * @access public
     * @param  BS_Traininglist_Model_Curriculum $curriculum
     * @return BS_Traininglist_Model_Curriculum_Category
     * @author Bui Phong
     */
    public function saveCurriculumRelation($curriculum)
    {
        $data = $curriculum->getCategoriesData();
        if (!is_null($data)) {
            $this->_getResource()->saveCurriculumRelation($curriculum, $data);
        }
        return $this;
    }

    /**
     * get categories for training curriculum
     *
     * @access public
     * @param BS_Traininglist_Model_Curriculum $curriculum
     * @return BS_Traininglist_Model_Resource_Curriculum_Category_Collection
     * @author Bui Phong
     */
    public function getCategoryCollection($curriculum)
    {
        $collection = Mage::getResourceModel('bs_traininglist/curriculum_category_collection')
            ->addCurriculumFilter($curriculum);
        return $collection;
    }
}
