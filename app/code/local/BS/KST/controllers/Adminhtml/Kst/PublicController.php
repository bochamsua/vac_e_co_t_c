<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_PublicController extends BS_KST_Controller_Adminhtml_KST
{
    public function readAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {

                $currentUserId = Mage::getSingleton('admin/session')->getUser()->getId();


                $news = Mage::getModel('bs_news/user')->getCollection()->addFieldToFilter('news_id',$this->getRequest()->getParam('id'))
                    ->addFieldToFilter('users_id', $currentUserId)
                    ->getFirstItem()
                ;

                //$nss = $news->getSelect()->__toString();

                $news->setMarkRead(1)->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('News was successfully mark as read.')
                );
                $this->_redirect('*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('adminhtml')->__('There was an error marking news as read.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('adminhtml')->__('Could not find news to set.')
        );
        $this->_redirect('*/');
    }


    protected function _isAllowed()
    {
        return true;
    }
}
