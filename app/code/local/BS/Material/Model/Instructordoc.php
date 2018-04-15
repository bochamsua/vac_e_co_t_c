<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Document model
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Instructordoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_material_instructordoc';
    const CACHE_TAG = 'bs_material_instructordoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_material_instructordoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'instructordoc';
    protected $_instructorInstance = null;

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
        $this->_init('bs_material/instructordoc');
    }

    /**
     * before save instructor doc
     *
     * @access protected
     * @return BS_Material_Model_Instructordoc
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
     * save instructor doc relation
     *
     * @access public
     * @return BS_Material_Model_Instructordoc
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getInstructorInstance()->saveInstructordocRelation($this);
        return parent::_afterSave();
    }

    /**
     * get instructor relation model
     *
     * @access public
     * @return BS_Material_Model_Instructordoc_Instructor
     * @author Bui Phong
     */
    public function getInstructorInstance()
    {
        if (!$this->_instructorInstance) {
            $this->_instructorInstance = Mage::getSingleton('bs_material/instructordoc_instructor');
        }
        return $this->_instructorInstance;
    }

    /**
     * get selected instructors array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedInstructors()
    {
        if (!$this->hasSelectedInstructors()) {
            $instructors = array();
            foreach ($this->getSelectedInstructorsCollection() as $instructor) {
                $instructors[] = $instructor;
            }
            $this->setSelectedInstructors($instructors);
        }
        return $this->getData('selected_instructors');
    }

    /**
     * Retrieve collection selected instructors
     *
     * @access public
     * @return BS_Material_Resource_Instructordoc_Instructor_Collection
     * @author Bui Phong
     */
    public function getSelectedInstructorsCollection()
    {
        $collection = $this->getInstructorInstance()->getInstructorCollection($this);
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
