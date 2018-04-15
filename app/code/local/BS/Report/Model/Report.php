<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report model
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Model_Report extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_report_report';
    const CACHE_TAG = 'bs_report_report';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_report_report';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'report';

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
        $this->_init('bs_report/report');
    }

    /**
     * before save individual report
     *
     * @access protected
     * @return BS_Report_Model_Report
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);

        $update = $now;
        $update = $update->format('Y-m-d H:m:s');

        if ($this->isObjectNew()) {
            $hour = intval($now->format('H'));
            if($hour < 12){
                $now->sub(new DateInterval('P1D'));
            }
            $now = $now->format('Y-m-d H:m:s');

            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($update);
        return $this;
    }

    /**
     * save individual report relation
     *
     * @access public
     * @return BS_Report_Model_Report
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
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
