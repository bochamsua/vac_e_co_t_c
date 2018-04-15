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
 * Curriculum Document curriculum model
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Curriculumdoc_Curriculum extends Mage_Core_Model_Abstract
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
        $this->_init('bs_curriculumdoc/curriculumdoc_curriculum');
    }

    /**
     * Save data for curriculum doc-curriculum relation
     * @access public
     * @param  BS_CurriculumDoc_Model_Curriculumdoc $curriculumdoc
     * @return BS_CurriculumDoc_Model_Curriculumdoc_Curriculum
     * @author Bui Phong
     */
    public function saveCurriculumdocRelation($curriculumdoc)
    {
        $data = $curriculumdoc->getCurriculumsData();
        if (!is_null($data)) {
            $this->_getResource()->saveCurriculumdocRelation($curriculumdoc, $data);
        }
        return $this;
    }

    /**
     * get curriculums for curriculum doc
     *
     * @access public
     * @param BS_CurriculumDoc_Model_Curriculumdoc $curriculumdoc
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum_Collection
     * @author Bui Phong
     */
    public function getCurriculumCollection($curriculumdoc)
    {
        $collection = Mage::getResourceModel('bs_curriculumdoc/curriculumdoc_curriculum_collection')
            ->addCurriculumdocFilter($curriculumdoc);
        return $collection;
    }
}
