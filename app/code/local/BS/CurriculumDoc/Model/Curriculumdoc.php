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
 * Curriculum Document model
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Curriculumdoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_curriculumdoc_curriculumdoc';
    const CACHE_TAG = 'bs_curriculumdoc_curriculumdoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_curriculumdoc_curriculumdoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'curriculumdoc';
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
        $this->_init('bs_curriculumdoc/curriculumdoc');
    }

    /**
     * before save curriculum doc
     *
     * @access protected
     * @return BS_CurriculumDoc_Model_Curriculumdoc
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
     * save curriculum doc relation
     *
     * @access public
     * @return BS_CurriculumDoc_Model_Curriculumdoc
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getCurriculumInstance()->saveCurriculumdocRelation($this);
        return parent::_afterSave();
    }

    /**
     * get curriculum relation model
     *
     * @access public
     * @return BS_CurriculumDoc_Model_Curriculumdoc_Curriculum
     * @author Bui Phong
     */
    public function getCurriculumInstance()
    {
        if (!$this->_curriculumInstance) {
            $this->_curriculumInstance = Mage::getSingleton('bs_curriculumdoc/curriculumdoc_curriculum');
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
     * @return BS_CurriculumDoc_Resource_Curriculumdoc_Curriculum_Collection
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
