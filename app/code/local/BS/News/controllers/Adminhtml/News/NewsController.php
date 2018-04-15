<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News admin controller
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Adminhtml_News_NewsController extends BS_News_Controller_Adminhtml_News
{
    /**
     * init the news
     *
     * @access protected
     * @return BS_News_Model_News
     */
    protected function _initNews()
    {
        $newsId  = (int) $this->getRequest()->getParam('id');
        $news    = Mage::getModel('bs_news/news');
        if ($newsId) {
            $news->load($newsId);
        }
        Mage::register('current_news', $news);
        return $news;
    }

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
        $this->_title(Mage::helper('bs_news')->__('News'))
             ->_title(Mage::helper('bs_news')->__('Newses'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit news - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $newsId    = $this->getRequest()->getParam('id');
        $news      = $this->_initNews();
        if ($newsId && !$news->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_news')->__('This news no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getNewsData(true);
        if (!empty($data)) {
            $news->setData($data);
        }
        Mage::register('news_data', $news);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_news')->__('News'))
             ->_title(Mage::helper('bs_news')->__('Newses'));
        if ($news->getId()) {
            $this->_title($news->getTitle());
        } else {
            $this->_title(Mage::helper('bs_news')->__('Add news'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function viewAction()
    {


        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new news action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save news - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('news')) {
            try {
                $data = $this->_filterDates($data, array('date_from' ,'date_to'));
                $news = $this->_initNews();


                $create = false;
                if(!$news->getId()){
                    $currentUser = Mage::getSingleton('admin/session')->getUser();
                    $userId = $currentUser->getId();
                    $data['user_id'] = $userId;
                    $create = true;
                }

                $news->addData($data);
                $news->save();

                $newsId = $news->getId();

                $sendTo = $news->getApplyFor();

                //remove all relation

                $relations = Mage::getModel('bs_news/user')->getCollection()->addFieldToFilter('news_id', $newsId);
                if($relations->count()){
                    $relations->walk('delete');
                }

                if($sendTo != ''){
                    $sendTo = explode(",", $sendTo);
                    foreach ($sendTo as $id) {
                        $relation = Mage::getModel('bs_news/user');
                        $relation->setNewsId($newsId)
                            ->setUsersId($id)
                            ->setMarkRead(0)
                            ->setReadTime(Mage::getSingleton('core/date')->gmtDate())
                        ;
                        $relation->save();

                    }
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_news')->__('News was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $news->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setNewsData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_news')->__('There was a problem saving the news.')
                );
                Mage::getSingleton('adminhtml/session')->setNewsData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_news')->__('Unable to find news to save.')
        );
        $this->_redirect('*/*/');
    }

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
                    Mage::helper('bs_news')->__('News was successfully mark as read.')
                );
                $this->_redirect('*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_news')->__('There was an error marking news as read.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_news')->__('Could not find news to set.')
        );
        $this->_redirect('*/');
    }
    /**
     * delete news - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $news = Mage::getModel('bs_news/news');
                $news->setId($this->getRequest()->getParam('id'))->delete();


                //remove all relation

                $relations = Mage::getModel('bs_news/user')->getCollection()->addFieldToFilter('news_id', $this->getRequest()->getParam('id'));
                if($relations->count()){
                    $relations->walk('delete');
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_news')->__('News was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_news')->__('There was an error deleting news.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_news')->__('Could not find news to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete news - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $newsIds = $this->getRequest()->getParam('news');
        if (!is_array($newsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_news')->__('Please select newses to delete.')
            );
        } else {
            try {
                foreach ($newsIds as $newsId) {
                    $news = Mage::getModel('bs_news/news');
                    $news->setId($newsId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_news')->__('Total of %d newses were successfully deleted.', count($newsIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_news')->__('There was an error deleting newses.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $newsIds = $this->getRequest()->getParam('news');
        if (!is_array($newsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_news')->__('Please select newses.')
            );
        } else {
            try {
                foreach ($newsIds as $newsId) {
                $news = Mage::getSingleton('bs_news/news')->load($newsId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d newses were successfully updated.', count($newsIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_news')->__('There was an error updating newses.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'news.csv';
        $content    = $this->getLayout()->createBlock('bs_news/adminhtml_news_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'news.xls';
        $content    = $this->getLayout()->createBlock('bs_news/adminhtml_news_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'news.xml';
        $content    = $this->getLayout()->createBlock('bs_news/adminhtml_news_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/news');
    }
}
