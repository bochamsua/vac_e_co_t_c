<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Category helper
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Helper_Category extends BS_Tasktraining_Helper_Data
{

    /**
     * get the selected instructor for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedTaskinstructors(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedTaskinstructors()) {
            $taskinstructors = array();
            foreach ($this->getSelectedTaskinstructorsCollection($category) as $taskinstructor) {
                $taskinstructors[] = $taskinstructor;
            }
            $category->setSelectedTaskinstructors($taskinstructors);
        }
        return $category->getData('selected_taskinstructors');
    }

    /**
     * get instructor collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return BS_Tasktraining_Model_Resource_Taskinstructor_Collection
     * @author Bui Phong
     */
    public function getSelectedTaskinstructorsCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('bs_tasktraining/taskinstructor_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
