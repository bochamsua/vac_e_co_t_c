<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document admin controller
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Adminhtml_Coursedoc_CoursedocController extends BS_CourseDoc_Controller_Adminhtml_CourseDoc
{
    /**
     * init the course doc
     *
     * @access protected
     * @return BS_CourseDoc_Model_Coursedoc
     */
    protected function _initCoursedoc()
    {
        $coursedocId  = (int) $this->getRequest()->getParam('id');
        $coursedoc    = Mage::getModel('bs_coursedoc/coursedoc');
        if ($coursedocId) {
            $coursedoc->load($coursedocId);
        }
        Mage::register('current_coursedoc', $coursedoc);
        return $coursedoc;
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
        $this->_title(Mage::helper('bs_coursedoc')->__('Course Document'))
             ->_title(Mage::helper('bs_coursedoc')->__('Course Documents'));
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
     * edit course doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $coursedocId    = $this->getRequest()->getParam('id');
        $coursedoc      = $this->_initCoursedoc();
        if ($coursedocId && !$coursedoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_coursedoc')->__('This course doc no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCoursedocData(true);
        if (!empty($data)) {
            $coursedoc->setData($data);
        }
        Mage::register('coursedoc_data', $coursedoc);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_coursedoc')->__('Course Document'))
             ->_title(Mage::helper('bs_coursedoc')->__('Course Documents'));
        if ($coursedoc->getId()) {
            $this->_title($coursedoc->getCourseDocName());
        } else {
            $this->_title(Mage::helper('bs_coursedoc')->__('Add course doc'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new course doc action
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
     * save course doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('coursedoc')) {

            try {
                $data = $this->_filterDates($data, array('doc_date'));
                $coursedoc = $this->_initCoursedoc();
                $coursedoc->addData($data);
                $courseDocFileName = $this->_uploadAndGetName(
                    'course_doc_file',
                    Mage::helper('bs_coursedoc/coursedoc')->getFileBaseDir(),
                    $data
                );

                $coursedoc->setData('course_doc_file', $courseDocFileName);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $coursedoc->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }else {
                    if(isset($data['hidden_course_id']) && $data['hidden_course_id'] > 0){
                        $coursedoc->setProductsData(
                            array(
                                $data['hidden_course_id'] => array(
                                    'position' => ""
                                )
                            )
                        );


                    }
                }
                $coursedoc->save();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.document.location.href = \''.$this->getUrl('*/catalog_product/edit', array('id'=>$data['hidden_course_id'], 'back'=>'edit', 'tab'=>'product_info_tabs_coursedocs')).'\'; window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursedoc')->__('Course Document was successfully saved %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if($add != ''){
                        $this->_redirect('*/*/edit', array('id' => $coursedoc->getId(), 'tab' => 'product_info_tabs_coursedocs'));
                    }else {
                        $this->_redirect('*/*/edit', array('id' => $coursedoc->getId()));
                    }

                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['course_doc_file']['value'])) {
                    $data['course_doc_file'] = $data['course_doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCoursedocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['course_doc_file']['value'])) {
                    $data['course_doc_file'] = $data['course_doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursedoc')->__('There was a problem saving the course doc.')
                );
                Mage::getSingleton('adminhtml/session')->setCoursedocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursedoc')->__('Unable to find course doc to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete course doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $coursedoc = Mage::getModel('bs_coursedoc/coursedoc');
                $coursedoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursedoc')->__('Course Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursedoc')->__('There was an error deleting course doc.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursedoc')->__('Could not find course doc to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete course doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $coursedocIds = $this->getRequest()->getParam('coursedoc');
        if (!is_array($coursedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursedoc')->__('Please select course docs to delete.')
            );
        } else {
            try {
                foreach ($coursedocIds as $coursedocId) {
                    $coursedoc = Mage::getModel('bs_coursedoc/coursedoc');
                    $coursedoc->setId($coursedocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursedoc')->__('Total of %d course docs were successfully deleted.', count($coursedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursedoc')->__('There was an error deleting course docs.')
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
        $coursedocIds = $this->getRequest()->getParam('coursedoc');
        if (!is_array($coursedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursedoc')->__('Please select course docs.')
            );
        } else {
            try {
                foreach ($coursedocIds as $coursedocId) {
                $coursedoc = Mage::getSingleton('bs_coursedoc/coursedoc')->load($coursedocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course docs were successfully updated.', count($coursedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursedoc')->__('There was an error updating course docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Document Type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCourseDocTypeAction()
    {
        $coursedocIds = $this->getRequest()->getParam('coursedoc');
        if (!is_array($coursedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursedoc')->__('Please select course docs.')
            );
        } else {
            try {
                foreach ($coursedocIds as $coursedocId) {
                $coursedoc = Mage::getSingleton('bs_coursedoc/coursedoc')->load($coursedocId)
                    ->setCourseDocType($this->getRequest()->getParam('flag_course_doc_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course docs were successfully updated.', count($coursedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursedoc')->__('There was an error updating course docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Revision change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCourseDocRevAction()
    {
        $coursedocIds = $this->getRequest()->getParam('coursedoc');
        if (!is_array($coursedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursedoc')->__('Please select course docs.')
            );
        } else {
            try {
                foreach ($coursedocIds as $coursedocId) {
                $coursedoc = Mage::getSingleton('bs_coursedoc/coursedoc')->load($coursedocId)
                    ->setCourseDocRev($this->getRequest()->getParam('flag_course_doc_rev'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course docs were successfully updated.', count($coursedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursedoc')->__('There was an error updating course docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsAction()
    {
        $this->_initCoursedoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('coursedoc.edit.tab.product')
            ->setCoursedocProducts($this->getRequest()->getPost('coursedoc_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsgridAction()
    {
        $this->_initCoursedoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('coursedoc.edit.tab.product')
            ->setCoursedocProducts($this->getRequest()->getPost('coursedoc_products', null));
        $this->renderLayout();
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
        $fileName   = 'coursedoc.csv';
        $content    = $this->getLayout()->createBlock('bs_coursedoc/adminhtml_coursedoc_grid')
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
        $fileName   = 'coursedoc.xls';
        $content    = $this->getLayout()->createBlock('bs_coursedoc/adminhtml_coursedoc_grid')
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
        $fileName   = 'coursedoc.xml';
        $content    = $this->getLayout()->createBlock('bs_coursedoc/adminhtml_coursedoc_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/coursedoc');
    }
}
