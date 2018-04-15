<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document - trainee relation edit block
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Trainee extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access protected
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('trainee_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getTraineedoc()->getId()) {
            $this->setDefaultFilter(array('in_trainees'=>1));
        }
    }

    /**
     * prepare the trainee collection
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Trainee
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_trainee/trainee_collection');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute('trainee_name', 'bs_trainee_trainee/trainee_name', 'entity_id', null, 'left', $adminStore);
        $collection->joinAttribute('trainee_code', 'bs_trainee_trainee/trainee_code', 'entity_id', null, 'left', $adminStore);
        $collection->joinAttribute('vaeco_id', 'bs_trainee_trainee/vaeco_id', 'entity_id', null, 'left', $adminStore);
        if ($this->getTraineedoc()->getId()) {
            $constraint = '{{table}}.traineedoc_id='.$this->getTraineedoc()->getId();
        } else {
            $constraint = '{{table}}.traineedoc_id=0';
        }
        $collection->joinField(
            'position',
            'bs_traineedoc/traineedoc_trainee',
            'position',
            'trainee_id=entity_id',
            $constraint,
            'left'
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Trainee
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
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Trainee
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_trainees',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_trainees',
                'values'=> $this->_getSelectedTrainees(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'trainee_name',
            array(
                'header'    => Mage::helper('catalog')->__('Trainee Name'),
                'align'     => 'left',
                'index'     => 'trainee_name',
                'renderer'  => 'bs_traineedoc/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/trainee_trainee/edit',
            )
        );
        $this->addColumn(
            'trainee_code',
            array(
                'header' => Mage::helper('bs_trainee')->__('Trainee ID'),
                'align'  => 'left',
                'index'  => 'trainee_code',

            )
        );
        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_trainee')->__('VAECO ID'),
                'align'  => 'left',
                'index'  => 'vaeco_id',

            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('catalog')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
    }

    /**
     * Retrieve selected trainees
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedTrainees()
    {
        $trainees = $this->getTraineedocTrainees();
        if (!is_array($trainees)) {
            $trainees = array_keys($this->getSelectedTrainees());
        }
        return $trainees;
    }

    /**
     * Retrieve selected trainees
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedTrainees()
    {
        $trainees = array();
        $selected = Mage::registry('current_traineedoc')->getSelectedTrainees();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $trainee) {
            $trainees[$trainee->getId()] = array('position' => $trainee->getPosition());
        }
        return $trainees;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_TraineeDoc_Model_Trainee
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
            '*/*/traineesGrid',
            array(
                'id' => $this->getTraineedoc()->getId()
            )
        );
    }

    /**
     * get the current trainee document
     *
     * @access public
     * @return BS_TraineeDoc_Model_Traineedoc
     * @author Bui Phong
     */
    public function getTraineedoc()
    {
        return Mage::registry('current_traineedoc');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_TraineeDoc_Block_Adminhtml_Traineedoc_Edit_Tab_Trainee
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in trainee flag
        if ($column->getId() == 'in_trainees') {
            $traineeIds = $this->_getSelectedTrainees();
            if (empty($traineeIds)) {
                $traineeIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $traineeIds));
            } else {
                if ($traineeIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $traineeIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
