<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Kstprogress extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_kst_kstprogress';
    const CACHE_TAG = 'bs_kst_kstprogress';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_kst_kstprogress';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'kstprogress';

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
        $this->_init('bs_kst/kstprogress');
    }

    /**
     * before save progress
     *
     * @access protected
     * @return BS_KST_Model_Kstprogress
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
     * get the url to the progress details page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getKstprogressUrl()
    {
        return Mage::getUrl('bs_kst/kstprogress/view', array('id'=>$this->getId()));
    }

    /**
     * save progress relation
     *
     * @access public
     * @return BS_KST_Model_Kstprogress
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
     * @return null|BS_KST_Model_Kstsubject
     * @author Bui Phong
     */
    public function getParentKstsubject()
    {
        if (!$this->hasData('_parent_kstsubject')) {
            if (!$this->getKstsubjectId()) {
                return null;
            } else {
                $kstsubject = Mage::getModel('bs_kst/kstsubject')
                    ->load($this->getKstsubjectId());
                if ($kstsubject->getId()) {
                    $this->setData('_parent_kstsubject', $kstsubject);
                } else {
                    $this->setData('_parent_kstsubject', null);
                }
            }
        }
        return $this->getData('_parent_kstsubject');
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_KST_Model_Kstitem
     * @author Bui Phong
     */
    public function getParentKstitem()
    {
        if (!$this->hasData('_parent_kstitem')) {
            if (!$this->getKstitemId()) {
                return null;
            } else {
                $kstitem = Mage::getModel('bs_kst/kstitem')
                    ->load($this->getKstitemId());
                if ($kstitem->getId()) {
                    $this->setData('_parent_kstitem', $kstitem);
                } else {
                    $this->setData('_parent_kstitem', null);
                }
            }
        }
        return $this->getData('_parent_kstitem');
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
