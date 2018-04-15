<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor category model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Instructor_Category extends Mage_Core_Model_Abstract
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
        $this->_init('bs_instructor/instructor_category');
    }

    /**
     * Save data for instructor-category relation
     *
     * @access public
     * @param  BS_Instructor_Model_Instructor $instructor
     * @return BS_Instructor_Model_Instructor_Category
     * @author Bui Phong
     */
    public function saveInstructorRelation($instructor)
    {
        $data = $instructor->getCategoriesData();
        if (!is_null($data)) {
            $this->_getResource()->saveInstructorRelation($instructor, $data);
        }
        return $this;
    }

    /**
     * get categories for instructor
     *
     * @access public
     * @param BS_Instructor_Model_Instructor $instructor
     * @return BS_Instructor_Model_Resource_Instructor_Category_Collection
     * @author Bui Phong
     */
    public function getCategoryCollection($instructor)
    {
        $collection = Mage::getResourceModel('bs_instructor/instructor_category_collection')
            ->addInstructorFilter($instructor);
        return $collection;
    }
}
