<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News model
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Model_News extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_news_news';
    const CACHE_TAG = 'bs_news_news';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_news_news';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'news';

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
        $this->_init('bs_news/news');
    }

    /**
     * before save news
     *
     * @access protected
     * @return BS_News_Model_News
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
     * get the news Content
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getContent()
    {
        $content = $this->getData('content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($content);
        return $html;
    }

    /**
     * save news relation
     *
     * @access public
     * @return BS_News_Model_News
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

    public function getAllNews($single = false){
        //$currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
        $collection = Mage::getModel('bs_news/news')->getCollection();

        $collection->getSelect()->joinLeft(array('user'=>'bs_news_user'),'user.news_id = main_table.entity_id','users_id');

        $currentUserId = Mage::getSingleton('admin/session')->getUser()->getId();
        $collection->addFieldToFilter('users_id', $currentUserId);
        $collection->addFieldToFilter('mark_read', 0);



        if($collection->count()){
            if($single){
                return $collection->getFirstItem();
            }
            return $collection;
        }

        return false;
    }
    /**
     * get Apply for
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    /*public function getApplyFor()
    {
        if (!$this->getData('apply_for')) {
            return explode(',', $this->getData('apply_for'));
        }
        return $this->getData('apply_for');
    }*/

    public function getCompleteCourse($days = 3, $count = false){
        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $today = new DateTime('now', $myTimezone);

        $str = 'P'.$days.'D';
        $interval = new DateInterval($str);

        $before = $today->sub($interval);

        $beforeDate = $before->format('Y-m-d');

        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('course_report', array(array('eq'=>0),array('null'=>true)))
            ->addAttributeToFilter('onhold', array(array('eq'=>0),array('null'=>true)))
            ->addAttributeToFilter('course_start_date', array('to' => $beforeDate))
            ->addAttributeToFilter('course_finish_date',array('to' => $beforeDate))
        ;

        if($count){
            return $collection->count();
        }

        $result = array();
        if($collection->count()){
            foreach ($collection as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getId());

                $result[] = array(
                    'sku'   => $product->getSku(),
                    'url'   => Mage::getModel('core/url')->getUrl("*/catalog_product/edit", array('id'=>$product->getId()))
                );
            }

            return $result;
        }

        return false;

    }

}
