<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject collection resource model
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Model_Resource_Subject_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('bs_subject/subject');
    }

    /**
     * get subjects as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Bui Phong
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='subject_name', $additional=array())
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
    protected function _toOptionHash($valueField='entity_id', $labelField='subject_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    public function toFullOptionArray(){
        return $this->_toFullOptionArray('entity_id','subject_name');
    }

    protected function _toFullOptionArray($valueField='id', $labelField='name', $additional=array())
    {
        $res = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            $add = $item->getData('subject_note');
            $hour = '';
            if($item->getData('subject_code') != ''){

                if($item->getData('subject_hour') != ''){
                    $hour .= ' -- ('.$item->getData('subject_hour').' hours)';
                }
            }
            foreach ($additional as $code => $field) {


                if($field == 'entity_id'){
                    $data[$code] = $item->getData($field);
                }else {
                    $data[$code] = $item->getData($field).' -- '.$add.$hour;
                }

            }
            $res[] = $data;
        }
        return $res;
    }


    /**
     * add the curriculum filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Curriculum|int) $curriculum
     * @return BS_Subject_Model_Resource_Subject_Collection
     * @author Bui Phong
     */
    public function addCurriculumFilter($curriculum)
    {
        if ($curriculum instanceof BS_Traininglist_Model_Curriculum) {
            $curriculum = $curriculum->getId();
        }
        if (!isset($this->_joinedFields['curriculum'])) {
            $this->getSelect()->join(
                array('related_curriculum' => $this->getTable('bs_subject/subject_curriculum')),
                'related_curriculum.subject_id = e.entity_id',
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
