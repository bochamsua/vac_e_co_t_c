<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet model
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Model_Worksheet extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_worksheet_worksheet';
    const CACHE_TAG = 'bs_worksheet_worksheet';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_worksheet_worksheet';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'worksheet';
    protected $_curriculumInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_worksheet/worksheet');
    }

    /**
     * before save worksheet
     *
     * @access protected
     * @return BS_Worksheet_Model_Worksheet
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save worksheet relation
     *
     * @access public
     * @return BS_Worksheet_Model_Worksheet
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getCurriculumInstance()->saveWorksheetRelation($this);
        return parent::_afterSave();
    }

    /**
     * get curriculum relation model
     *
     * @access public
     * @return BS_Worksheet_Model_Worksheet_Curriculum
     * @author Bui Phong
     */
    public function getCurriculumInstance()
    {
        if (!$this->_curriculumInstance) {
            $this->_curriculumInstance = Mage::getSingleton('bs_worksheet/worksheet_curriculum');
        }
        return $this->_curriculumInstance;
    }

    /**
     * get selected curriculums array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedCurriculums()
    {
        if (!$this->hasSelectedCurriculums()) {
            $curriculums = array();
            foreach ($this->getSelectedCurriculumsCollection() as $curriculum) {
                $curriculums[] = $curriculum;
            }
            $this->setSelectedCurriculums($curriculums);
        }
        return $this->getData('selected_curriculums');
    }

    /**
     * Retrieve collection selected curriculums
     *
     * @access public
     * @return BS_Worksheet_Resource_Worksheet_Curriculum_Collection
     * @author Bui Phong
     */
    public function getSelectedCurriculumsCollection()
    {
        $collection = $this->getCurriculumInstance()->getCurriculumCollection($this);
        return $collection;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
