<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum helper
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Helper_Curriculum extends BS_CurriculumDoc_Helper_Data
{

    /**
     * get the selected curriculum docs for a curriculum
     *
     * @access public
     * @param Mage_Catalog_Model_Curriculum $curriculum
     * @return array()
     * @author Bui Phong
     */
    public function getSelectedCurriculumdocs(BS_Traininglist_Model_Curriculum $curriculum)
    {
        if (!$curriculum->hasSelectedCurriculumdocs()) {
            $curriculumdocs = array();
            foreach ($this->getSelectedCurriculumdocsCollection($curriculum) as $curriculumdoc) {
                $curriculumdocs[] = $curriculumdoc;
            }
            $curriculum->setSelectedCurriculumdocs($curriculumdocs);
        }
        return $curriculum->getData('selected_curriculumdocs');
    }

    /**
     * get curriculum doc collection for a curriculum
     *
     * @access public
     * @param Mage_Catalog_Model_Curriculum $curriculum
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Collection
     * @author Bui Phong
     */
    public function getSelectedCurriculumdocsCollection(BS_Traininglist_Model_Curriculum $curriculum)
    {
        $collection = Mage::getResourceSingleton('bs_curriculumdoc/curriculumdoc_collection')
            ->addCurriculumFilter($curriculum);
        return $collection;
    }
}
