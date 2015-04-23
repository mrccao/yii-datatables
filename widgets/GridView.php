<?php

Yii::import('system.zii.widgets.grid.CGridView');

class GridView extends CGridView
{

    /**
     * @var CClientscript.
     */
    protected $cs;

    /**
     * @var array the HTML options for the table tag.
     */
    public $tableHtmlOptions = array();

    /**
     * @var string ID table.
     */
    protected $tableId;

    /**
     * @var string theme name.
     * Valid values are 'base', 'bootstrap', 'jqueryUI' or 'foundation'.
     * if you choose theme 'bootstrap' - you can specify additional classes for the table (e.g. 'table-bordered' or/and 'table-striped and etc.)
     */
    public $theme = "base";

    /**
     * @var string type of data sources.
     */
    public $dataSources;

    /**
     * @var boolean disable border-bottom css styling of table.
     */
    public $disableBorderBottom = false;

    /**
     * @var boolean enable ajax pagination, sorting and filtering.
     */
    public $enableAjax = false;

    /**
     * @var array the configuration for the pager.
     * Defaults to <code>array('class'=>'datatables.widgets.LinkPager')</code>.
     */
    public $pager = array('class' => 'datatables.widgets.LinkPager');

    /**
     * @var array options for datatables jquery plugin.
     */

    public $options = array();

    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate {@link columns} objects.
     */
    public function init()
    {
        /*$this->options['ajax'] = is_null($this->options['ajax']) ? $this->ajaxUrl : $this->options['ajax'];
        if (!in_array($this->dataSources, array('html', 'ajax', 'javascript', 'server-side')))
            $this->dataSources = 'html';
        if ($this->dataSources == 'server-side')
            $this->options['serverSide'] = true;
        if ($this->dataSources == 'ajax' && is_null($this->options['ajax'])) {
            throw new CException('Error! Not specified property "ajax" in the plugin settings.');
        }
        if (is_string($this->options['ajax']) && $this->ajaxType == 'POST') {
            $this->options['ajax'] = array(
                'url' => $this->options['ajax'],
                'method' => $this->ajaxType
            );
            if (Yii::app()->request->enableCsrfValidation)
                $this->options['ajax']['data'] = is_array($this->options['ajax']['data']) || is_null($this->options['ajax']['data']) ? CMap::mergeArray($this->options['ajax']['data'], array(Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken)) : array(Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken);
        } else if (is_array($this->options['ajax']) && $this->ajaxType == 'POST') {
            $this->options['ajax']['method'] = $this->ajaxType;
            if (Yii::app()->request->enableCsrfValidation)
                $this->options['ajax']['data'] = is_array($this->options['ajax']['data']) || is_null($this->options['ajax']['data']) ? CMap::mergeArray($this->options['ajax']['data'], array(Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken)) : array(Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken);
        }
        if ($this->tableHtmlOptions['data-paging']) {
            $this->template = str_replace('{pager}', '', $this->template);
            $this->options['pageLength'] = $this->dataProvider->pagination->pageSize;
        }
        if ($this->tableHtmlOptions['data-ordering']) {
            $this->enableSorting = false;
        }*/

        parent::init();

        if (is_null($this->cs))
            $this->cs = Yii::app()->getClientScript();

        $this->initDatatables();
    }

    protected function initDatatables()
    {
        $this->tableId = is_null($this->tableId) ? ($this->tableHtmlOptions['id'] ? $this->tableHtmlOptions['id'] : uniqid()) : uniqid();
        $this->tableHtmlOptions['id'] = $this->tableId;
        if (!in_array($this->dataSources, array('html', 'ajax', 'javascript', 'server-side')))
            $this->dataSources = 'html';
        if ($this->dataSources != 'html')
            throw new Exception("The specified data source - {$this->dataSources}, in the process of developing."); // TODO later to be work
            //$this->initDataSources; // TODO
        if (!is_array($this->tableHtmlOptions)) {
            $this->tableHtmlOptions = array(
                'data-paging' => $this->dataSources = 'html' ? false : true,
                'data-searching' => false,
                'data-ordering' => false
            );
        } else {
            $this->tableHtmlOptions = CMap::mergeArray(array(
                //'data-paging' => $this->dataSources == 'html' ? false : true,
                'data-searching' => false,
                'data-ordering' => $this->dataSources == 'server-side' ? true : false
            ), $this->tableHtmlOptions);
        }
        if (!is_array($this->tableHtmlOptions))
            throw new Exception('Property "tableHtmlOptions" should only be an array.');
        if (isset($this->theme) && in_array($this->theme, array('base', 'bootstrap', 'jqueryUI', 'foundation')))
            $this->initTheme();
        $this->tableHtmlOptions['class'] = empty($this->tableHtmlOptions['class']) ? 'dataTable' : $this->tableHtmlOptions['class'] . ' dataTable';
    }

    /**
     *### .initDataSources()
     *
     * Initialize data sources.
     */
    protected function initDataSources()
    {
        // TODO
    }

    /**
     *### .initTheme()
     *
     * Initialize theme.
     */
    protected function initTheme()
    {
        switch ($this->theme) {
            case "base":
                $this->pagerCssClass = 'dataTables_wrapper';
                break;
            case "bootstrap":
                $this->pagerCssClass = 'pull-right';
                $this->pager['htmlOptions']['class'] = 'pagination';
                $this->pager['activeLinkClass'] = 'active';
                $this->tableHtmlOptions['class'] .= ' table';
                break;
            case "jqueryUI":
                $this->pagerCssClass = 'dataTables_wrapper fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix';
                $this->cs->registerCssFile(
                    $this->cs->getCoreScriptUrl().
                    '/jui/css/base/jquery-ui.css'
                );
                break;
            case "foundation":
                $this->pager['htmlOptions']['class'] = 'pagination';
                $this->pagerCssClass = 'dataTables_paginate';
                break;
        }
    }

    /**
     *### .initColumns()
     *
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class'])) {
                $this->columns[$i]['class'] = 'datatables.widgets.DataColumn';
            }
        }

        parent::initColumns();
    }

    public function run()
    {

        $this->registerClientScript();

        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";

        $this->renderContent();

        echo CHtml::closeTag($this->tagName);

        $this->registerScripts();

    }

    public function renderItems()
    {
        if ($this->dataProvider->getItemCount() > 0 || $this->showTableOnEmpty) {
            echo CHtml::openTag('table', $this->tableHtmlOptions) . "\n";
            $this->renderTableHeader();
            ob_start();
            $this->renderTableBody();
            $body = ob_get_clean();
            $this->renderTableFooter();
            echo $body; // TFOOT must appear before TBODY according to the standard.
            echo CHtml::closeTag('table');
        } else
            $this->renderEmptyText();
    }

    /**
     * Renders the table header.
     */
    public function renderTableHeader()
    {
        if ($this->dataSources == 'javascript')
            return;
        if (!$this->hideHeader) {
            echo "<thead>\n";

            echo "<tr>\n";
            $sort = $this->dataProvider->getSort();
            foreach ($this->columns as $index => $column) {
                if ($column->sortable && $this->tableHtmlOptions['data-ordering']) {
                    $column->headerHtmlOptions['data-name'] = $column->name;
                }
                if (!$column->sortable && $this->tableHtmlOptions['data-ordering']) {
                    $column->headerHtmlOptions['data-orderable'] = false;
                } elseif ($column->sortable && in_array($column->name, array_keys($sort->directions))) {
                    $direction = $sort->directions[$column->name] ? 'asc' : 'desc';
                    $column->headerHtmlOptions['class'] .= ' sorting_' . $direction;
                } elseif ($column->sortable) {
                    $column->headerHtmlOptions['class'] .= ' sorting';
                }
                $column->renderHeaderCell();
            }
            echo "</tr>\n";

            if ($this->filterPosition === self::FILTER_POS_HEADER)
                $this->renderFilter();

            echo "</thead>\n";
        } elseif ($this->filter !== null && ($this->filterPosition === self::FILTER_POS_HEADER || $this->filterPosition === self::FILTER_POS_BODY)) {
            echo "<thead>\n";
            $this->renderFilter();
            echo "</thead>\n";
        }
    }

    public function renderTableBody()
    {

        if ($this->dataSources != 'html')
            return;
        $data = $this->dataProvider->getData();
        $n = count($data);
        echo "<tbody>\n";

        if ($this->filterPosition === self::FILTER_POS_BODY)
            $this->renderFilter();

        if ($n > 0) {
            for ($row = 0; $row < $n; ++$row)
                $this->renderTableRow($row);
        } else {
            echo '<tr><td colspan="' . count($this->columns) . '" class="empty">';
            $this->renderEmptyText();
            echo "</td></tr>\n";
        }
        echo "</tbody>\n";
    }

    public function registerClientScript()
    {
        $id = $this->tableId;
        Yii::app()->clientScript->registerPackage('datatables');
        if($this->theme == 'jqueryUI')
            $this->cs->registerPackage('datatables.jqueryUI');
        if($this->theme == 'foundation') {
            $this->cs->registerPackage('datatables.foundation');
            $this->cs->registerCssFile('//cdn.jsdelivr.net/foundation/5.5.1/css/foundation.min.css');
        }
        if(!$this->options['dom'] && $this->theme == 'jqueryUI')
            $this->options['dom'] = '<"fg-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"lfr>t<"fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">';
        elseif(!$this->options['dom']) {
            $this->options['dom'] = 'lfrt';
        }
        $options = CJavaScript::encode($this->options);
        $this->cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#$id').DataTable($options); $.fn.dataTableExt.sErrMode = 'function';");
        if ($this->disableBorderBottom) {
            $this->cs->registerCss(__CLASS__ . 'disableBorderBottom', "table.dataTable thead th, table.dataTable thead td {border-bottom: none !important;} .dataTables_wrapper.no-footer .dataTables_scrollBody {border-bottom: none !important;}");
        }
    }

    protected function registerScripts()
    {
        if ($this->enableAjax) {
            $this->registerPagination();
            $this->registerSorting();
            $this->registerFiltering();
        }
    }

    protected function registerPagination()
    {
        $id = $this->getId();
        $pagerCssClass = $this->pagerCssClass ? ".{$this->pagerCssClass}" : "";
        $pagerCssClass = empty(explode(' ',trim($pagerCssClass))[0]) ? $pagerCssClass : explode(' ',trim($pagerCssClass))[0];
        $this->cs->registerScript(__CLASS__ . '#' . $id . '_pagination', "$('#$id $pagerCssClass ul li').on('click', function(event) {
            event.preventDefault();
            if($(this).hasClass('disabled')) {
                return false;
            }
            var link = $(this).find('a');
            if ($.support.pjax) {
                $.pjax({
                    async: false,
                    push: false,
                    url: $(link).attr('href'),
                    container: '#$id'
                })
            }
            else {
                $.ajax({
                    url: $(link).attr('href'),
                    data: {
                       ajax: '$id'
                    },
                    success: function(data) {
                        var html = $(data).filter(':not(script[src])').filter(':not(link)');
                        $('#$id').html(html);
                    }
                })
            }
        });");
    }

    protected function registerSorting()
    {
        $id = $this->getId();
        $this->cs->registerScript(__CLASS__ . '#' . $id . '_sorting', "jQuery('#$id table thead th[class*=sorting]').on('click', function(event) {
            event.preventDefault();
            var link = $(this).find('a');
            if ($.support.pjax) {
                $.pjax({
                    async: false,
                    push: false,
                    url: $(link).attr('href'),
                    container: '#$id'
                })
            }
            else {
                $.ajax({
                    data: {
                        ajax: '$id'
                    },
                    url: $(link).attr('href'),
                    success: function(data) {
                        var html = $(data).filter(':not(script[src])').filter(':not(link)');
                        $('#$id').html(html);
                    }
                })
            }
        });");
    }

    protected function registerFiltering() {
        $id = $this->getId();
        $pager = new CLinkPager($this->pager);
        $url = $pager->getPages()->createPageUrl($this->getController(), $pager->getPages()->currentPage);
        $this->cs->registerScript(__CLASS__ . '#' . $id . '_filtering', "jQuery('#$id table thead tr.{$this->filterCssClass} td input').on('change', function(event) {
            event.preventDefault();
            var value = $(this).val();
            var name = $(this).attr('name');
            var data = {};
            data[name] = value;
            if ($.support.pjax) {
                $.pjax({
                    async: false,
                    push: false,
                    url: '$url',
                    data: data,
                    container: '#$id'
                })
            }
            else {
                data.ajax = '$id';
                $.ajax({
                    data: data,
                    url: '$url',
                    success: function(data) {
                        var html = $(data).filter(':not(script[src])').filter(':not(link)');
                        $('#$id').html(html);
                    }
                })
            }
        });");
    }

}