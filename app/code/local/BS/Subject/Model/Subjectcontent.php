<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content model
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Model_Subjectcontent extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_subject_subjectcontent';
    const CACHE_TAG = 'bs_subject_subjectcontent';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_subject_subjectcontent';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'subjectcontent';

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
        $this->_init('bs_subject/subjectcontent');
    }

    /**
     * before save subject content
     *
     * @access protected
     * @return BS_Subject_Model_Subjectcontent
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
     * save subject content relation
     *
     * @access public
     * @return BS_Subject_Model_Subjectcontent
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Subject_Model_Subject
     * @author Bui Phong
     */
    public function getParentSubject()
    {
        if (!$this->hasData('_parent_subject')) {
            if (!$this->getSubjectId()) {
                return null;
            } else {
                $subject = Mage::getModel('bs_subject/subject')
                    ->load($this->getSubjectId());
                if ($subject->getId()) {
                    $this->setData('_parent_subject', $subject);
                } else {
                    $this->setData('_parent_subject', null);
                }
            }
        }
        return $this->getData('_parent_subject');
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
