<?php
/**
 * BS_Traininglist extension
 *
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum admin grid block
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Block_Adminhtml_Curriculum_History_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('curriculumhistoryGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }


    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_traininglist/curriculum')
            ->getCollection()
            //->addAttributeToSelect('status')
            ->addAttributeToFilter('c_history', 1);

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute('c_name','bs_traininglist_curriculum/c_name','entity_id',null,'inner',$adminStore);
        $collection->joinAttribute('c_code','bs_traininglist_curriculum/c_code','entity_id',null,'inner',$adminStore);
        $collection->joinAttribute('c_rev','bs_traininglist_curriculum/c_rev','entity_id',null,'inner',$adminStore);
        $collection->joinAttribute('c_aircraft','bs_traininglist_curriculum/c_aircraft','entity_id',null,'inner',$adminStore);
        $collection->joinAttribute('c_approved_date','bs_traininglist_curriculum/c_approved_date','entity_id',null,'inner',$adminStore);
        if ($store->getId()) {
            $collection->joinAttribute('
                bs_traininglist_curriculum_c_name',
                'bs_traininglist_curriculum/c_name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
        }


        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Traininglist_Block_Adminhtml_Curriculum_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'c_name',
            array(
                'header'    => Mage::helper('bs_traininglist')->__('Curriculum Name'),
                'align'     => 'left',
                'index'     => 'c_name',
                'renderer' => 'bs_traininglist/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => '*/traininglist_curriculum/edit',
            )
        );
        $this->addColumn(
            'c_code',
            array(
                'header'    => Mage::helper('bs_traininglist')->__('Curriculum Code'),
                'align'     => 'left',
                'index'     => 'c_code',
            )
        );

        $this->addColumn(
            'c_aircraft',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Applicable for A/C'),
                'index'  => 'c_aircraft',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_aircraft')->getSource()->getAllOptions(false)
                )

            )
        );




        $this->addColumn(
            'c_approved_date',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Curriculum Approved Date'),
                'index'  => 'c_approved_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'c_rev',
            array(
                'header' => Mage::helper('bs_traininglist')->__('Curriculum Revision'),
                'index'  => 'c_rev',
                'type'  => 'options',
                'options' => Mage::helper('bs_traininglist')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', 'c_rev')->getSource()->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_traininglist')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_traininglist')->__('Edit'),
                        'url'     => array('base'=> '*/traininglist_curriculum/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        //$this->addExportType('*/*/exportCsv', Mage::helper('bs_traininglist')->__('CSV'));
        //$this->addExportType('*/*/exportExcel', Mage::helper('bs_traininglist')->__('Excel'));
        //$this->addExportType('*/*/exportXml', Mage::helper('bs_traininglist')->__('XML'));
        return parent::_prepareColumns();
    }


    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('curriculum');


        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/curriculum/curriculum/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_traininglist')->__('Delete'),
                    'url'  => $this->getUrl('*/traininglist_curriculum/massDelete', array('back'=>'traininglist_curriculum_history')),
                    'confirm'  => Mage::helper('bs_traininglist')->__('Are you sure?')
                )
            );
        }

        $this->getMassactionBlock()->addItem(
            'generate',
            array(
                'label'      => Mage::helper('bs_traininglist')->__('Generate Word Files'),
                'url'        => $this->getUrl('*/traininglist_curriculum/massGenerate', array('backto'=>'history')),

            )
        );



        return $this;
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

    public function getRowUrl($row)
    {
        return $this->getUrl('*/traininglist_curriculum/edit', array('id' => $row->getId()));
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
        return $this->getUrl('*/traininglist_curriculum_history/grid', array('_current'=>true));
    }
}
