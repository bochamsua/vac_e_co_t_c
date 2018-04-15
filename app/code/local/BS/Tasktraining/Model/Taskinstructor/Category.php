<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor category model
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Model_Taskinstructor_Category extends Mage_Core_Model_Abstract
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
        $this->_init('bs_tasktraining/taskinstructor_category');
    }

    /**
     * Save data for instructor-category relation
     *
     * @access public
     * @param  BS_Tasktraining_Model_Taskinstructor $taskinstructor
     * @return BS_Tasktraining_Model_Taskinstructor_Category
     * @author Bui Phong
     */
    public function saveTaskinstructorRelation($taskinstructor)
    {
        $data = $taskinstructor->getCategoriesData();
        if (!is_null($data)) {
            $this->_getResource()->saveTaskinstructorRelation($taskinstructor, $data);
        }
        return $this;
    }

    /**
     * get categories for instructor
     *
     * @access public
     * @param BS_Tasktraining_Model_Taskinstructor $taskinstructor
     * @return BS_Tasktraining_Model_Resource_Taskinstructor_Category_Collection
     * @author Bui Phong
     */
    public function getCategoryCollection($taskinstructor)
    {
        $collection = Mage::getResourceModel('bs_tasktraining/taskinstructor_category_collection')
            ->addTaskinstructorFilter($taskinstructor);
        return $collection;
    }
}
