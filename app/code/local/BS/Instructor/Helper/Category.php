<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Category helper
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Helper_Category extends BS_Instructor_Helper_Data
{

    /**
     * get the selected instructors for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedInstructors(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedInstructors()) {
            $instructors = array();
            foreach ($this->getSelectedInstructorsCollection($category) as $instructor) {
                $instructors[] = $instructor;
            }
            $category->setSelectedInstructors($instructors);
        }
        return $category->getData('selected_instructors');
    }

    /**
     * get instructor collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return BS_Instructor_Model_Resource_Instructor_Collection
     * @author Bui Phong
     */
    public function getSelectedInstructorsCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('bs_instructor/instructor_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
