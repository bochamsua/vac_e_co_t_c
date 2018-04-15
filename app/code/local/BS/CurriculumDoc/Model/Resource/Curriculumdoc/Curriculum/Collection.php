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
 * Curriculum Document - curriculum relation resource model collection
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum_Collection extends BS_Traininglist_Model_Resource_Curriculum_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_curriculumdoc/curriculumdoc_curriculum')),
                'related.curriculum_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add curriculum doc filter
     *
     * @access public
     * @param BS_CurriculumDoc_Model_Curriculumdoc | int $curriculumdoc
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum_Collection
     * @author Bui Phong
     */
    public function addCurriculumdocFilter($curriculumdoc)
    {
        if ($curriculumdoc instanceof BS_CurriculumDoc_Model_Curriculumdoc) {
            $curriculumdoc = $curriculumdoc->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.curriculumdoc_id = ?', $curriculumdoc);
        return $this;
    }
}
