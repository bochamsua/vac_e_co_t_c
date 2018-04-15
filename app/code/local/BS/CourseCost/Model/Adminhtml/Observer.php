<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_CourseCost_Model_Adminhtml_Observer
{
    public function saveCoursecostPosition($observer)
    {
        $post = Mage::app()->getRequest()->getPost('coursecostposition', -1);
        if ($post != '-1') {
            foreach ($post as $key => $value) {
                $schedule = Mage::getModel('bs_coursecost/coursecost')->load($key);
                $schedule->setCoursecostOrder($value)->save();
            }
        }
        return $this;
    }}
