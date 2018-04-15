<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Migration admin controller
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Adminhtml_Tools_MigrationController extends BS_Tools_Controller_Adminhtml_Tools
{
    /**
     * init the migration
     *
     * @access protected
     * @return BS_Tools_Model_Migration
     */
    protected function _initMigration()
    {
        $migrationId  = (int) $this->getRequest()->getParam('id');
        $migration    = Mage::getModel('bs_tools/migration');
        if ($migrationId) {
            $migration->load($migrationId);
        }
        Mage::register('current_migration', $migration);
        return $migration;
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
        $this->_title(Mage::helper('bs_tools')->__('Miscellaneous'))
             ->_title(Mage::helper('bs_tools')->__('Migrations'));
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
     * edit migration - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $migrationId    = $this->getRequest()->getParam('id');
        $migration      = $this->_initMigration();
        if ($migrationId && !$migration->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_tools')->__('This migration no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getMigrationData(true);
        if (!empty($data)) {
            $migration->setData($data);
        }
        Mage::register('migration_data', $migration);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_tools')->__('Miscellaneous'))
             ->_title(Mage::helper('bs_tools')->__('Migrations'));
        if ($migration->getId()) {
            $this->_title($migration->getName());
        } else {
            $this->_title(Mage::helper('bs_tools')->__('Add migration'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new migration action
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
     * save migration - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('migration')) {
            try {

                $import = $data['id_code'];
                $import = explode("\r\n", $import);
                $nothing = true;
                $count  = 0;
                foreach ($import as $line) {
                    if (strpos($line, "-")) {
                        $item = explode("-", $line);
                    } else {
                        $item = explode("\t", $line);
                    }

                    if(count($item) == 2){
                        $traineeCode = $item[0];
                        $vaecoId = $item[1];

                        //Update Trainee first
                        $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $traineeCode)->getFirstItem();
                        if($tn->getId()){
                            $nothing = false;
                            $trainee = Mage::getModel('bs_trainee/trainee')->load($tn->getId());
                            $trainee->setVaecoId($vaecoId)->save();
                        }

                        //Update Docwise trainee
                        $dwtn = Mage::getModel('bs_docwise/trainee')->getCollection()->addFieldToFilter('vaeco_id', $traineeCode)->getFirstItem();
                        if($dwtn->getId()){
                            $nothing = false;
                            $dwtn->setVaecoId($vaecoId)->save();
                        }

                        //Update Docwise score
                        $dwScore = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('vaeco_id', $traineeCode);
                        if($dwScore->count()){
                            $nothing = false;
                            foreach ($dwScore as $item) {
                                $item->setVaecoId($vaecoId)->save();
                            }
                        }

                        //now update Staff


                        if(!$nothing){
                            $count += 1;
                        }
                    }


                }

                if($nothing){
                    Mage::getSingleton('adminhtml/session')->addWarning(
                        Mage::helper('bs_tools')->__('The Trainee Code does not exist in any instance')
                    );
                }else {
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_tools')->__('%s items have been updated!', $count)
                    );
                }

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/new');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setMigrationData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was a problem saving the migration.')
                );
                Mage::getSingleton('adminhtml/session')->setMigrationData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tools')->__('Unable to find migration to save.')
        );
        $this->_redirect('*/*/new');
    }

    /**
     * delete migration - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $migration = Mage::getModel('bs_tools/migration');
                $migration->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tools')->__('Migration was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error deleting migration.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tools')->__('Could not find migration to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete migration - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $migrationIds = $this->getRequest()->getParam('migration');
        if (!is_array($migrationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tools')->__('Please select migrations to delete.')
            );
        } else {
            try {
                foreach ($migrationIds as $migrationId) {
                    $migration = Mage::getModel('bs_tools/migration');
                    $migration->setId($migrationId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tools')->__('Total of %d migrations were successfully deleted.', count($migrationIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error deleting migrations.')
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
        $migrationIds = $this->getRequest()->getParam('migration');
        if (!is_array($migrationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tools')->__('Please select migrations.')
            );
        } else {
            try {
                foreach ($migrationIds as $migrationId) {
                $migration = Mage::getSingleton('bs_tools/migration')->load($migrationId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d migrations were successfully updated.', count($migrationIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error updating migrations.')
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
        $fileName   = 'migration.csv';
        $content    = $this->getLayout()->createBlock('bs_tools/adminhtml_migration_grid')
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
        $fileName   = 'migration.xls';
        $content    = $this->getLayout()->createBlock('bs_tools/adminhtml_migration_grid')
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
        $fileName   = 'migration.xml';
        $content    = $this->getLayout()->createBlock('bs_tools/adminhtml_migration_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_tools/migration');
    }
}
