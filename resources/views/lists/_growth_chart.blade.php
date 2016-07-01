<!-- Basic column chart -->
<div class="panel panel-flat">
    <div class="panel-body">
        <div class="chart-container">
            <div class="chart has-fixed-height" id="basic_columns" data-url="{{ action('MailListController@listGrowthChart', $list->uid) }}"></div>
        </div>
    </div>
</div>
<!-- /basic column chart -->