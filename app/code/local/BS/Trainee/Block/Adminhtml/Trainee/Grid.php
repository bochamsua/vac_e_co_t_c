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
 * Trainee admin grid block
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('traineeGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Trainee_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_trainee/trainee')
            ->getCollection()
            ->addAttributeToSelect('*');
            //->addAttributeToSelect('vaeco_id')
            //->addAttributeToSelect('status');
        


        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Trainee_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_trainee')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'trainee_name',
            array(
                'header'    => Mage::helper('bs_trainee')->__('Trainee Name'),
                'align'     => 'left',
                'index'     => 'trainee_name',
            )
        );


        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_trainee')->__('VAECO ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'trainee_code',
            array(
                'header' => Mage::helper('bs_trainee')->__('Trainee ID'),
                'index'  => 'trainee_code',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'trainee_dob',
            array(
                'header' => Mage::helper('bs_trainee')->__('Birthday'),
                'index'  => 'trainee_dob',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'trainee_phone',
            array(
                'header' => Mage::helper('bs_trainee')->__('Phone'),
                'index'  => 'trainee_phone',
                'type'=> 'text',

            )
        );

        $this->addColumn(
            'trainee_function',
            array(
                'header' => Mage::helper('bs_trainee')->__('Function'),
                'index'  => 'trainee_function',
                'type'  => 'options',
                'options' => Mage::helper('bs_trainee')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_trainee_trainee', 'trainee_function')->getSource()->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'trainee_basic',
            array(
                'header' => Mage::helper('bs_trainee')->__('Basic'),
                'index'  => 'trainee_basic',
                'type'  => 'options',
                'options' => Mage::helper('bs_trainee')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_trainee_trainee', 'trainee_basic')->getSource()->getAllOptions(false)
                )

            )
        );


        $this->addColumn(
            'trainee_position',
            array(
                'header' => Mage::helper('bs_trainee')->__('Position'),
                'index'  => 'trainee_position',
                'type'  => 'options',
                'options' => Mage::helper('bs_trainee')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('bs_trainee_trainee', 'trainee_position')->getSource()->getAllOptions(false)
                )

            )
        );

        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_trainee')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_trainee')->__('Enabled'),
                    '0' => Mage::helper('bs_trainee')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_trainee')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_trainee')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_trainee')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_trainee')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_trainee')->__('XML'));
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
     * @return BS_Trainee_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('trainee');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/trainee/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/trainee/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_trainee')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_trainee')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'function',
                array(
                    'label'      => Mage::helper('bs_trainee')->__('Change Function'),
                    'url'        => $this->getUrl('*/*/massChangeFunction', array('_current'=>true)),
                    'additional' => array(
                        'function' => array(
                            'name'   => 'function',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_trainee')->__('Function'),
                            'values' => Mage::getModel('eav/config')->getAttribute('bs_trainee_trainee', 'trainee_function')->getSource()->getAllOptions(false)
                        )
                    ),
                    'confirm'  => Mage::helper('bs_trainee')->__('This action should be taken very carefully.Are you sure?')
                )
            );
            $this->getMassactionBlock()->addItem(
                'basic',
                array(
                    'label'      => Mage::helper('bs_trainee')->__('Change Basic'),
                    'url'        => $this->getUrl('*/*/massChangeBasic', array('_current'=>true)),
                    'additional' => array(
                        'basic' => array(
                            'name'   => 'basic',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_trainee')->__('Basic'),
                            'values' => Mage::getModel('eav/config')->getAttribute('bs_trainee_trainee', 'trainee_basic')->getSource()->getAllOptions(false)
                        )
                    ),
                    'confirm'  => Mage::helper('bs_trainee')->__('This action should be taken very carefully.Are you sure?')
                )
            );
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_trainee')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_trainee')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_trainee')->__('Enabled'),
                                '0' => Mage::helper('bs_trainee')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Trainee_Model_Trainee
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
