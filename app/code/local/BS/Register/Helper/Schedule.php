<?php 
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule helper
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Helper_Schedule extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the course schedule list page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getSchedulesUrl()
    {
        if ($listKey = Mage::getStoreConfig('bs_register/schedule/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('bs_register/schedule/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('bs_register/schedule/breadcrumbs');
    }
}
