<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content admin grid block
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subjectcontent_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('subjectcontentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_subject/subjectcontent')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('sub'=>'bs_subject_subject'),'subject_id = sub.entity_id','subject_name');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_subject')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'subject_id',
            array(
                'header'    => Mage::helper('bs_subject')->__('Subject'),
                'type'      => 'text',
                'renderer'  => 'bs_subject/adminhtml_helper_column_renderer_subject',
                'filter_condition_callback' => array($this, '_subjectFilter'),

            )
        );


        $this->addColumn(
            'subcon_title',
            array(
                'header'    => Mage::helper('bs_subject')->__('Content Title'),
                'align'     => 'left',
                'index'     => 'subcon_title',
            )
        );

        $this->addColumn(
            'subcon_code',
            array(
                'header'    => Mage::helper('bs_subject')->__('Code'),
                'align'     => 'left',
                'index'     => 'subcon_code',
            )
        );
        

        $this->addColumn(
            'subcon_level',
            array(
                'header' => Mage::helper('bs_subject')->__('Level'),
                'index'  => 'subcon_level',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'subcon_hour',
            array(
                'header' => Mage::helper('bs_subject')->__('Hour'),
                'index'  => 'subcon_hour',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subcon_order',
            array(
                'header' => Mage::helper('bs_subject')->__('Sort Order'),
                'index'  => 'subcon_order',
                'type'=> 'number',

            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_subject')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_subject')->__('Enabled'),
                    '0' => Mage::helper('bs_subject')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_subject')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_subject')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_subject')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_subject')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_subject')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _subjectFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->getSelect()->where(
            "subject_name LIKE ?"
            , "%$value%");


        return $this;
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('subjectcontent');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcontent/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/subject/subjectcontent/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_subject')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_subject')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'replace_title',
                array(
                    'label'      => Mage::helper('bs_subject')->__('Replace Name'),
                    'url'        => $this->getUrl('*/*/massReplacetitle', array('_current'=>true)),
                    'additional' => array(
                        'replace_title' => array(
                            'name'   => 'replace_title',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_subject')->__('Text | Replace'),
                        )
                    )
                )
            );




//        $values = Mage::getResourceModel('bs_subject/subject_collection')->toOptionHash();
//        $values = array_reverse($values, true);
//        $values[''] = '';
//        $values = array_reverse($values, true);
//        $this->getMassactionBlock()->addItem(
//            'subject_id',
//            array(
//                'label'      => Mage::helper('bs_subject')->__('Change Subject'),
//                'url'        => $this->getUrl('*/*/massSubjectId', array('_current'=>true)),
//                'additional' => array(
//                    'flag_subject_id' => array(
//                        'name'   => 'flag_subject_id',
//                        'type'   => 'select',
//                        'class'  => 'required-entry',
//                        'label'  => Mage::helper('bs_subject')->__('Subject'),
//                        'values' => $values
//                    )
//                )
//            )
//        );
        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Subject_Model_Subjectcontent
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

    /**
     * after collection load
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
