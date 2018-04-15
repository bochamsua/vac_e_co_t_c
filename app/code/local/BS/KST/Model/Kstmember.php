<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Member model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Kstmember extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_kst_kstmember';
    const CACHE_TAG = 'bs_kst_kstmember';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_kst_kstmember';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'kstmember';

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
        $this->_init('bs_kst/kstmember');
    }

    /**
     * before save member
     *
     * @access protected
     * @return BS_KST_Model_Kstmember
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
     * save member relation
     *
     * @access public
     * @return BS_KST_Model_Kstmember
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
     * @return null|BS_KST_Model_Kstgroup
     * @author Bui Phong
     */
    public function getParentKstgroup()
    {
        if (!$this->hasData('_parent_kstgroup')) {
            if (!$this->getKstgroupId()) {
                return null;
            } else {
                $kstgroup = Mage::getModel('bs_kst/kstgroup')
                    ->load($this->getKstgroupId());
                if ($kstgroup->getId()) {
                    $this->setData('_parent_kstgroup', $kstgroup);
                } else {
                    $this->setData('_parent_kstgroup', null);
                }
            }
        }
        return $this->getData('_parent_kstgroup');
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
