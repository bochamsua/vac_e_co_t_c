<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document collection resource model
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Resource_Traineedoc_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('bs_traineedoc/traineedoc');
    }

    /**
     * get trainee document as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='trainee_doc_name', $additional=array())
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
    protected function _toOptionHash($valueField='entity_id', $labelField='trainee_doc_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the trainee filter to collection
     *
     * @access public
     * @param mixed (BS_Trainee_Model_Trainee|int) $trainee
     * @return BS_TraineeDoc_Model_Resource_Traineedoc_Collection
     * @author Bui Phong
     */
    public function addTraineeFilter($trainee)
    {
        if ($trainee instanceof BS_Trainee_Model_Trainee) {
            $trainee = $trainee->getId();
        }
        if (!isset($this->_joinedFields['trainee'])) {
            $this->getSelect()->join(
                array('related_trainee' => $this->getTable('bs_traineedoc/traineedoc_trainee')),
                'related_trainee.traineedoc_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_trainee.trainee_id = ?', $trainee);
            $this->_joinedFields['trainee'] = true;
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
