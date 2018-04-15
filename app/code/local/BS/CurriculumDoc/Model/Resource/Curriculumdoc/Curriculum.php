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
 * Curriculum Document - curriculum relation model
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Bui Phong
     */
    protected function  _construct()
    {
        $this->_init('bs_curriculumdoc/curriculumdoc_curriculum', 'rel_id');
    }
    /**
     * Save curriculum doc - curriculum relations
     *
     * @access public
     * @param BS_CurriculumDoc_Model_Curriculumdoc $curriculumdoc
     * @param array $data
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum
     * @author Bui Phong
     */
    public function saveCurriculumdocRelation($curriculumdoc, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('curriculumdoc_id=?', $curriculumdoc->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $curriculumId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'curriculumdoc_id' => $curriculumdoc->getId(),
                    'curriculum_id'    => $curriculumId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  curriculum - curriculum doc relations
     *
     * @access public
     * @param Mage_Catalog_Model_Curriculum $prooduct
     * @param array $data
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Curriculum
     * @@author Bui Phong
     */
    public function saveCurriculumRelation($curriculum, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('curriculum_id=?', $curriculum->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $curriculumdocId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'curriculumdoc_id' => $curriculumdocId,
                    'curriculum_id'    => $curriculum->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
