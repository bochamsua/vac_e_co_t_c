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
class BS_Register_Block_Schedule_List extends Mage_Core_Block_Template
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

        //schedule/schedule/course/key/Qm8gU3VhIE1vb24gS2VuMjMz/go/MjAxNi0wMy0yOQ==
        /*$date = $this->getRequest()->getParam('go', false);
        if($date){
            $date = base64_decode($date);
            $currentDate = $date;
        }else {
            $currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
        }*/

        $date = $this->getDate();
        $currentDate = $date['date'];



        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('conducting_place')
            ->addAttributeToSelect('course_note')
            ->addAttributeToFilter('status',1)
            //->addAttributeToFilter('plan_dispatch_no',array('notnull' => true))
            ->addAttributeToFilter('course_start_date',
                array('to' => $currentDate))
            ->addAttributeToFilter('course_finish_date',
                array('from' => $currentDate)
            );

        ;






        $this->setSchedules($collection);
    }

    public function getDate($para = ''){
        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $date = $this->getRequest()->getParam('show', false);
        if($date) {
            //$date = urldecode($date);
            $today = new DateTime($date, $myTimezone);
        }else {
            $today = new DateTime('now', $myTimezone);
        }


        $current = $today;
        $date = $current->format('Y-m-d');
        $currentDisplay = $today->format('d-m-Y');

        $today->sub(new DateInterval('P1D'));
        $prevDate = $today->format('Y-m-d');
        $prevDisplay = $today->format('d-m-Y');

        $today->add(new DateInterval('P2D'));
        $nextDate = $today->format('Y-m-d');
        $nextDisplay = $today->format('d-m-Y');



        if($para == 'next'){
            return array(
                'url'   => $this->getUrl('schedule/schedule/course/', array('show' => $nextDisplay)),
                'date'  => $nextDate,
                'display'   => $nextDisplay
            );

        }elseif ($para == 'prev'){
            return array(
                'url'   => $this->getUrl('schedule/schedule/course/', array('show' => $prevDisplay)),
                'date'  => $prevDate,
                'display'   => $prevDisplay
            );
        }

        return array(
            'url'   => $this->getUrl('schedule/schedule/course/', array('')),
            'date'  => $date,
            'display'   => $currentDisplay
        );
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
        return parent::_prepareLayout();
        /*$pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_register.schedule.html.pager'
        )
        ->setCollection($this->getSchedules());
        $this->setChild('pager', $pager);*/
        //$this->getSchedules()->load();
        //return $this;
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

    public function getIcon($product){
        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);

        $todayDate = $now->format('Y-m-d');
        $todayDate .= ' 00:00:00.000000';

        $today = new DateTime($todayDate, $myTimezone);

        $startDate = new DateTime($product->getCourseStartDate(), $myTimezone);

        $finishDate = new DateTime($product->getCourseFinishDate(), $myTimezone);

        $interval = new DateInterval('P3D');

        $check = $today->diff($finishDate);
        $days = $check->days;


        $checkStart = $today->diff($startDate);
        $dayStart = $checkStart->days;

        $class = '';
        $add = '';


        if($dayStart == 0){
            $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/start.png" style="float: left; margin-right: 5px;">';
        }

        if($days == 0){
            $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/finished.png" style="float: left; margin-right: 5px;">';
        }


        /*if(trim($product->getPlanDispatchNo()) == ''){
            $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/caution.png" style="float: left; margin-right: 5px;">';
        }*/



        /*if($today > $finishDate){
            if(!$product->getDocuments() && $days > 3){
                $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/question.png" style="float: left; margin-right: 5px;">';
            }

            if(!$product->getHasReport() && $days > 10){
                $add .= '<img width="30" src="'.Mage::getBaseUrl('media').'images/warning.png" style="float: left; margin-right: 5px;">';
            }


        }*/

        return $add;

    }

    public function getStatus($product){
        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);

        $todayDate = $now->format('Y-m-d');
        $todayDate .= ' 00:00:00.000000';

        $today = new DateTime($todayDate, $myTimezone);

        $startDate = new DateTime($product->getCourseStartDate(), $myTimezone);

        $finishDate = new DateTime($product->getCourseFinishDate(), $myTimezone);

        $interval = new DateInterval('P3D');

        $check = $today->diff($finishDate);
        $days = $check->days;


        $checkStart = $today->diff($startDate);
        $dayStart = $checkStart->days;

        $class = '';
        $add = 'CONTINUE';


        if($dayStart == 0){
            $add = 'START';
            return $add;
        }

        if($days == 0){
            $add = 'FINISH';
            return $add;
        }


        return $add;



    }
}
