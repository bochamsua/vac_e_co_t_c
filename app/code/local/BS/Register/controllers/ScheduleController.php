<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule front contrller
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_ScheduleController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Bui Phong
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bs_register/schedule')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_register')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'schedules',
                    array(
                        'label' => Mage::helper('bs_register')->__('Course Schedule'),
                        'link'  => '',
                    )
                );
            }
        }

        $this->renderLayout();
    }

    public function courseAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bs_register/schedule')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_register')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'schedules',
                    array(
                        'label' => Mage::helper('bs_register')->__('Current Courses'),
                        'link'  => '',
                    )
                );
            }
        }

        $this->renderLayout();
    }

    public function comingAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bs_register/schedule')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_register')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'schedules',
                    array(
                        'label' => Mage::helper('bs_register')->__('Coming Courses'),
                        'link'  => '',
                    )
                );
            }
        }

        $this->renderLayout();
    }
    public function historyAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('bs_register/schedule')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('bs_register')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'schedules',
                    array(
                        'label' => Mage::helper('bs_register')->__('Completed Courses'),
                        'link'  => '',
                    )
                );
            }
        }

        $this->renderLayout();
    }

    /**
     * init Course Schedule
     *
     * @access protected
     * @return BS_Register_Model_Schedule
     * @author Bui Phong
     */
    protected function _initSchedule()
    {
        $scheduleId   = $this->getRequest()->getParam('id', 0);
        $schedule     = Mage::getModel('bs_register/schedule')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($scheduleId);
        if (!$schedule->getId()) {
            return false;
        } elseif (!$schedule->getStatus()) {
            return false;
        }
        return $schedule;
    }

    /**
     * view course schedule action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $schedule = $this->_initSchedule();
        if (!$schedule) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_schedule', $schedule);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('register-schedule register-schedule' . $schedule->getId());
        }
        if (Mage::helper('bs_register/schedule')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_register')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'schedules',
                    array(
                        'label' => Mage::helper('bs_register')->__('Course Schedule'),
                        'link'  => Mage::helper('bs_register/schedule')->getSchedulesUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'schedule',
                    array(
                        'label' => $schedule->getScheduleNote(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $schedule->getScheduleUrl());
        }
        $this->renderLayout();
    }
}
