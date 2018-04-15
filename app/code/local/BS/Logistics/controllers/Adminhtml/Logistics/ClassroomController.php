<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_ClassroomController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the classroom/examroom
     *
     * @access protected
     * @return BS_Logistics_Model_Classroom
     */
    protected function _initClassroom()
    {
        $classroomId  = (int) $this->getRequest()->getParam('id');
        $classroom    = Mage::getModel('bs_logistics/classroom');
        if ($classroomId) {
            $classroom->load($classroomId);
        }
        Mage::register('current_classroom', $classroom);
        return $classroom;
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
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Classroom/Examrooms'));
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
     * edit classroom/examroom - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $classroomId    = $this->getRequest()->getParam('id');
        $classroom      = $this->_initClassroom();
        if ($classroomId && !$classroom->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This classroom/examroom no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getClassroomData(true);
        if (!empty($data)) {
            $classroom->setData($data);
        }
        Mage::register('classroom_data', $classroom);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Classroom/Examrooms'));
        if ($classroom->getId()) {
            $this->_title($classroom->getClassroomName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add classroom/examroom'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new classroom/examroom action
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
     * save classroom/examroom - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('classroom')) {
            try {
                $classroom = $this->_initClassroom();
                $classroom->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $classroom->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $classroom->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Classroom/Examroom was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $classroom->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setClassroomData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the classroom/examroom.')
                );
                Mage::getSingleton('adminhtml/session')->setClassroomData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find classroom/examroom to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete classroom/examroom - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $classroom = Mage::getModel('bs_logistics/classroom');
                $classroom->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Classroom/Examroom was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting classroom/examroom.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find classroom/examroom to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete classroom/examroom - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $classroomIds = $this->getRequest()->getParam('classroom');
        if (!is_array($classroomIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select classroom/examrooms to delete.')
            );
        } else {
            try {
                foreach ($classroomIds as $classroomId) {
                    $classroom = Mage::getModel('bs_logistics/classroom');
                    $classroom->setId($classroomId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d classroom/examrooms were successfully deleted.', count($classroomIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting classroom/examrooms.')
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
        $classroomIds = $this->getRequest()->getParam('classroom');
        if (!is_array($classroomIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select classroom/examrooms.')
            );
        } else {
            try {
                foreach ($classroomIds as $classroomId) {
                $classroom = Mage::getSingleton('bs_logistics/classroom')->load($classroomId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d classroom/examrooms were successfully updated.', count($classroomIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating classroom/examrooms.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Location change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massClassroomLocationAction()
    {
        $classroomIds = $this->getRequest()->getParam('classroom');
        if (!is_array($classroomIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select classroom/examrooms.')
            );
        } else {
            try {
                foreach ($classroomIds as $classroomId) {
                $classroom = Mage::getSingleton('bs_logistics/classroom')->load($classroomId)
                    ->setClassroomLocation($this->getRequest()->getParam('flag_classroom_location'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d classroom/examrooms were successfully updated.', count($classroomIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating classroom/examrooms.')
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
        $this->_initClassroom();
        $this->loadLayout();
        $this->getLayout()->getBlock('classroom.edit.tab.product')
            ->setClassroomProducts($this->getRequest()->getPost('classroom_products', null));
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
        $this->_initClassroom();
        $this->loadLayout();
        $this->getLayout()->getBlock('classroom.edit.tab.product')
            ->setClassroomProducts($this->getRequest()->getPost('classroom_products', null));
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
        $fileName   = 'classroom.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_classroom_grid')
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
        $fileName   = 'classroom.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_classroom_grid')
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
        $fileName   = 'classroom.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_classroom_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/classroom');
    }
}
