<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule list block
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Block_Schedule_History extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();

        $currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));

        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('course_start_date')
            //->addAttributeToFilter('course_report',0)
            //->addAttributeToFilter('course_start_date', array('from' => $currentDate))
            ->addAttributeToFilter('course_finish_date',array('to' => $currentDate))
            ->addAttributeToFilter('sku', array('nlike'=>'VIRTUAL%'))
        ;

        $collection->setOrder('course_start_date', 'DESC');




        $this->setSchedules($collection);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Register_Block_Schedule_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_register.schedule.html.pager'
        )
            ->setCollection($this->getSchedules());
        $this->setChild('pager', $pager);
        $this->getSchedules()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
