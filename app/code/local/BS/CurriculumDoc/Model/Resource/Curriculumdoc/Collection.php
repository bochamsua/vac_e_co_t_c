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
 * Curriculum Document collection resource model
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Resource_Curriculumdoc_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('bs_curriculumdoc/curriculumdoc');
    }

    /**
     * get curriculum docs as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='cdoc_name', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='cdoc_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the curriculum filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Curriculum|int) $curriculum
     * @return BS_CurriculumDoc_Model_Resource_Curriculumdoc_Collection
     * @author Bui Phong
     */
    public function addCurriculumFilter($curriculum)
    {
        if ($curriculum instanceof BS_Traininglist_Model_Curriculum) {
            $curriculum = $curriculum->getId();
        }
        if (!isset($this->_joinedFields['curriculum'])) {
            $this->getSelect()->join(
                array('related_curriculum' => $this->getTable('bs_curriculumdoc/curriculumdoc_curriculum')),
                'related_curriculum.curriculumdoc_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_curriculum.curriculum_id = ?', $curriculum);
            $this->_joinedFields['curriculum'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Bui Phong
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
