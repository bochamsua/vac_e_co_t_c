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
class BS_Instructor_Helper_Curriculum extends BS_Instructor_Helper_Data
{

    /**
     * get the selected instructors for a curriculum
     *
     * @access public
     * @param Mage_Catalog_Model_Product $curriculum
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedInstructors(BS_Traininglist_Model_Curriculum $curriculum)
    {
        if (!$curriculum->hasSelectedInstructors()) {
            $instructors = array();
            foreach ($this->getSelectedInstructorsCollection($curriculum) as $instructor) {
                $instructors[] = $instructor;
            }
            $curriculum->setSelectedInstructors($instructors);
        }
        return $curriculum->getData('selected_instructors');
    }

    /**
     * get instructor collection for a curriculum
     *
     * @access public
     * @param Mage_Catalog_Model_Product $curriculum
     * @return BS_Instructor_Model_Resource_Instructor_Collection
     * @author Bui Phong
     */
    public function getSelectedInstructorsCollection(BS_Traininglist_Model_Curriculum $curriculum)
    {
        $collection = Mage::getResourceSingleton('bs_instructor/instructor_collection')
            ->addCurriculumFilter($curriculum);
        return $collection;
    }
}
