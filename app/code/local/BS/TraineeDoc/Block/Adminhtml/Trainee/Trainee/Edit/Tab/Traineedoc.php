<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document tab on trainee edit form
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Block_Adminhtml_Trainee_Trainee_Edit_Tab_Traineedoc extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('traineedoc_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getTrainee()->getId()) {
            $this->setDefaultFilter(array('in_traineedocs'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_traineedoc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add New Document'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/traineedoc_traineedoc/new', array('_current'=>false, 'trainee_id'=>$this->getTrainee()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_traineedoc_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }
    /**
     * prepare the traineedoc collection
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Trainee_Trainee_Edit_Tab_Traineedoc
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_traineedoc/traineedoc_collection');
        if ($this->getTrainee()->getId()) {
            $constraint = 'related.trainee_id='.$this->getTrainee()->getId();
        } else {
            $constraint = 'related.trainee_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_traineedoc/traineedoc_trainee')),
            'related.traineedoc_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Trainee_Trainee_Edit_Tab_Traineedoc
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Trainee_Trainee_Edit_Tab_Traineedoc
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_traineedocs',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_traineedocs',
                'values'=> $this->_getSelectedTraineedocs(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'trainee_doc_name',
            array(
                'header' => Mage::helper('bs_traineedoc')->__('Document Name'),
                'align'  => 'left',
                'index'  => 'trainee_doc_name',
                'renderer' => 'bs_traineedoc/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/traineedoc_traineedoc/edit',
            )
        );
        $this->addColumn(
            'trainee_doc_type',
            array(
                'header' => Mage::helper('bs_traineedoc')->__('Document Type'),
                'index'  => 'trainee_doc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_traineedoc')->convertOptions(
                    Mage::getModel('bs_traineedoc/traineedoc_attribute_source_traineedoctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_traineedoc')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_traineedoc/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('bs_traineedoc')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected traineedocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedTraineedocs()
    {
        $traineedocs = $this->getTraineeTraineedocs();
        if (!is_array($traineedocs)) {
            $traineedocs = array_keys($this->getSelectedTraineedocs());
        }
        return $traineedocs;
    }

    /**
     * Retrieve selected traineedocs
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedTraineedocs()
    {
        $traineedocs = array();
        //used helper here in order not to override the trainee model
        $selected = Mage::helper('bs_traineedoc/trainee')->getSelectedTraineedocs(Mage::registry('current_trainee'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $traineedoc) {
            $traineedocs[$traineedoc->getId()] = array('position' => $traineedoc->getPosition());
        }
        return $traineedocs;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_TraineeDoc_Model_Traineedoc
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/traineedocsGrid',
            array(
                'id'=>$this->getTrainee()->getId()
            )
        );
    }

    /**
     * get the current trainee
     *
     * @access public
     * @return BS_Trainee_Model_Trainee
     * @author Bui Phong
     */
    public function getTrainee()
    {
        return Mage::registry('current_trainee');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_TraineeDoc_Block_Adminhtml_Trainee_Trainee_Edit_Tab_Traineedoc
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_traineedocs') {
            $traineedocIds = $this->_getSelectedTraineedocs();
            if (empty($traineedocIds)) {
                $traineedocIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$traineedocIds));
            } else {
                if ($traineedocIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$traineedocIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
