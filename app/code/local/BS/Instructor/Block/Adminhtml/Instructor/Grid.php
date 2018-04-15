<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin grid block
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Block_Adminhtml_Instructor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('instructorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_instructor/instructor')
            ->getCollection()
            ->addAttributeToSelect('*');

        //$collection->getSelect()->joinLeft(array('cvar'=>'customer_entity_varchar'),'e.vaeco_id')
        
//        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
//        $store = $this->_getStore();
//        $collection->joinAttribute(
//            'iname',
//            'bs_instructor_instructor/iname',
//            'entity_id',
//            null,
//            'inner',
//            $adminStore
//        );
//        $collection->joinAttribute(
//            'ivaecoid',
//            'bs_instructor_instructor/ivaecoid',
//            'entity_id',
//            null,
//            'inner',
//            $adminStore
//        );
//        if ($store->getId()) {
//            $collection->joinAttribute('
//                bs_instructor_instructor_iname',
//                'bs_instructor_instructor/iname',
//                'entity_id',
//                null,
//                'inner',
//                $store->getId()
//            );
//        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructor')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'iname',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Instructor Name'),
                'align'     => 'left',
                'index'     => 'iname',
            )
        );

        $this->addColumn(
            'ivaecoid',
            array(
                'header'    => Mage::helper('bs_instructor')->__('VAECO ID'),
                'align'     => 'left',
                'index'     => 'ivaecoid',
            )
        );


        $this->addColumn(
            'iusername',
            array(
                'header'    => Mage::helper('bs_instructor')->__('VAECO Username'),
                'align'     => 'left',
                'index'     => 'iusername',
            )
        );

        $this->addColumn(
            'iphone',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Phone'),
                'align'     => 'left',
                'index'     => 'iphone',
            )
        );

        $this->addColumn(
            'iposition',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Position'),
                'align'     => 'left',
                'index'     => 'iposition',
            )
        );

        $this->addColumn(
            'idivision_department',
            array(
                'header'    => Mage::helper('bs_instructor')->__('Division - Department'),
                'align'     => 'left',
                'index'     => 'idivision_department',
            )
        );
        $this->addColumn(
            'ifilefolder',
            array(
                'header'    => Mage::helper('bs_instructor')->__('File Folder'),
                'align'     => 'left',
                'index'     => 'ifilefolder',
            )
        );





        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_instructor')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_instructor')->__('Enabled'),
                    '0' => Mage::helper('bs_instructor')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_instructor')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_instructor')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_instructor')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_instructor')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_instructor')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Bui Phong
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Instructor_Block_Adminhtml_Instructor_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('instructor');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructor/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/bs_instructor/instructor/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_instructor')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_instructor')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'ifilefolder',
                array(
                    'label'      => Mage::helper('bs_instructor')->__('Change File Folder'),
                    'url'        => $this->getUrl('*/*/massFilefolder', array('_current'=>true)),
                    'additional' => array(
                        'ifilefolder' => array(
                            'name'   => 'ifilefolder',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructor')->__('File Folder'),
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'fortytwo',
                array(
                    'label'      => Mage::helper('bs_instructor')->__('Generate 8042'),
                    'url'        => $this->getUrl('*/*/massGenerateFortytwo', array('_current'=>true)),
                )
            );

            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_instructor')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_instructor')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_instructor')->__('Enabled'),
                                '0' => Mage::helper('bs_instructor')->__('Disabled'),
                            )
                        )
                    )
                )
            );


            $this->getMassactionBlock()->addItem(
                'updateinfo',
                array(
                    'label'=> Mage::helper('bs_instructor')->__('Update Info'),
                    'url'  => $this->getUrl('*/*/massUpdateInfo'),
                    'confirm'  => Mage::helper('bs_instructor')->__('Are you sure?')
                )
            );




        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Instructor_Model_Instructor
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
