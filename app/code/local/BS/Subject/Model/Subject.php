<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject model
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Model_Subject extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_subject_subject';
    const CACHE_TAG = 'bs_subject_subject';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_subject_subject';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'subject';

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
        $this->_init('bs_subject/subject');
    }

    /**
     * before save subject
     *
     * @access protected
     * @return BS_Subject_Model_Subject
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
     * save subject relation
     *
     * @access public
     * @return BS_Subject_Model_Subject
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    public function getParentCurriculum()
    {
        if (!$this->hasData('_parent_curriculum')) {
            if (!$this->getCurriculumId()) {
                return null;
            } else {
                $curriculum = Mage::getCurriculumId('bs_traininglist/curriculum')
                    ->load($this->getCurriculumId());
                if ($curriculum->getId()) {
                    $this->setData('_parent_curriculum', $curriculum);
                } else {
                    $this->setData('_parent_curriculum', null);
                }
            }
        }
        return $this->getData('_parent_curriculum');
    }
    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Subject_Model_Subjectcontent_Collection
     * @author Bui Phong
     */
    public function getSelectedSubjectcontentsCollection()
    {
        if (!$this->hasData('_subjectcontent_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_subject/subjectcontent_collection')
                        ->addFieldToFilter('subject_id', $this->getId());
                $this->setData('_subjectcontent_collection', $collection);
            }
        }
        return $this->getData('_subjectcontent_collection');
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
