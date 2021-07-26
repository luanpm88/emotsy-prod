class List {
    constructor(options) {
        var _this = this;
        _this.id = '_' + Math.random().toString(36).substr(2, 9);
        _this.options = {};

        // options
        if (typeof(options) !== 'undefined') {
            _this.options = options;
        }
        
        // url
        if (typeof(options.url) !== 'undefined') {
            _this.url = options.url;
        } else {
            alert('List url is required!');
            return;
        }

        // content
        if (typeof(options.content) !== 'undefined') {
            _this.content = options.content;
        } else {
            alert('List content is required!');
            return;
        }
        
        // data
        if (typeof(options.data) !== 'undefined') {
            _this.data = options.data;
        } else {
            _this.data = function() { return {}; };
        }

        // method
        if (typeof(options.method) !== 'undefined') {
            _this.method = options.method
        } else {
            _this.method = 'GET';
        }

        this.content.html(`
            <table class="table table-box pml-table border-top mt-2" current-page="1"><tbody>
            <tr><td width="1%"><div class="text-nowrap"><div class="checkbox inline"><label><input type="checkbox" class="node styled" name="uids[]" value="60fbcde0d8691"></label></div></div></td><td><h5 class="text-bold mb-2"><a class="kq_search" href="https://ui.com/campaigns/60fbcde0d8691">asdasda asdas dasd asd asd asd as dasd asd as  as</a></h5><div class="mb-2"><span class="text-semibold tooltipstered" data-popup="tooltip">500 Recipients</span></div><span class="text-muted2">Updated at: Jul 24th, 2021 08:22</span></td><td></td><td></td><td></td><td width="15%" class="text-center"><span class="text-muted2 list-status pull-left" title="" data-popup="tooltip"><span class="label label-flat bg-new">New a</span></span><pre style="display:none"></pre></td><td class="text-end text-nowrap"><div class="d-flex align-items-center text-nowrap justify-content-end"><a href="https://ui.com/campaigns/60fbcde0d8691/edit" role="button" class="btn btn-secondary btn-icon ms-1"> <span class="material-icons-outlined">edit</span> Edit</a><div class="btn-group ms-1" role="group"><button id="btnGroupDrop1" role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"><span class="caret ml-0"></span></button><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1"><li><a class="copy-campaign-button dropdown-item" href="https://ui.com/campaigns/copy?uid=60fbcde0d8691&amp;copy_campaign_uid=60fbcde0d8691"><span class="material-icons-outlined me-2">copy_all</span> Copy</a></li><li><a class="dropdown-item list-action-single" link-method="POST" link-confirm="You're about to delete <span class=&quot;fw-600&quot;>asdasda</span> campaign. This can not be undone." href="https://ui.com/campaigns/delete?uids=60fbcde0d8691"><span class="material-icons-outlined me-2">delete_outline</span> Delete</a></li></ul></div></div></td></tr>
            <tr><td width="1%"><div class="text-nowrap"><div class="checkbox inline"><label><input type="checkbox" class="node styled" name="uids[]" value="60fbcde0d8691"></label></div></div></td><td><h5 class="text-bold mb-2"><a class="kq_search" href="https://ui.com/campaigns/60fbcde0d8691">asdasdaas asd asd asd asasas asd as</a></h5><div class="mb-2"><span class="text-semibold tooltipstered" data-popup="tooltip">500 Recipients</span></div><span class="text-muted2">Updated at: Jul 24th, 2021 08:22</span></td><td></td><td></td><td></td><td width="15%" class="text-center"><span class="text-muted2 list-status pull-left" title="" data-popup="tooltip"><span class="label label-flat bg-new">New ass</span></span><pre style="display:none"></pre></td><td class="text-end text-nowrap"><div class="d-flex align-items-center text-nowrap justify-content-end"><a href="https://ui.com/campaigns/60fbcde0d8691/edit" role="button" class="btn btn-secondary btn-icon ms-1"> <span class="material-icons-outlined">edit</span> Edit</a><div class="btn-group ms-1" role="group"><button id="btnGroupDrop1" role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"><span class="caret ml-0"></span></button><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1"><li><a class="copy-campaign-button dropdown-item" href="https://ui.com/campaigns/copy?uid=60fbcde0d8691&amp;copy_campaign_uid=60fbcde0d8691"><span class="material-icons-outlined me-2">copy_all</span> Copy</a></li><li><a class="dropdown-item list-action-single" link-method="POST" link-confirm="You're about to delete <span class=&quot;fw-600&quot;>asdasda</span> campaign. This can not be undone." href="https://ui.com/campaigns/delete?uids=60fbcde0d8691"><span class="material-icons-outlined me-2">delete_outline</span> Delete</a></li></ul></div></div></td></tr>
            <tr><td width="1%"><div class="text-nowrap"><div class="checkbox inline"><label><input type="checkbox" class="node styled" name="uids[]" value="60fbcde0d8691"></label></div></div></td><td><h5 class="text-bold mb-2"><a class="kq_search" href="https://ui.com/campaigns/60fbcde0d8691">asdasdaa sdsa da asd asd asd asd as dasd asd </a></h5><div class="mb-2"><span class="text-semibold tooltipstered" data-popup="tooltip">500 Recipients</span></div><span class="text-muted2">Updated at: Jul 24th, 2021 08:22</span></td><td></td><td></td><td></td><td width="15%" class="text-center"><span class="text-muted2 list-status pull-left" title="" data-popup="tooltip"><span class="label label-flat bg-new">New</span></span><pre style="display:none"></pre></td><td class="text-end text-nowrap"><div class="d-flex align-items-center text-nowrap justify-content-end"><a href="https://ui.com/campaigns/60fbcde0d8691/edit" role="button" class="btn btn-secondary btn-icon ms-1"> <span class="material-icons-outlined">edit</span> Edit</a><div class="btn-group ms-1" role="group"><button id="btnGroupDrop1" role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"><span class="caret ml-0"></span></button><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1"><li><a class="copy-campaign-button dropdown-item" href="https://ui.com/campaigns/copy?uid=60fbcde0d8691&amp;copy_campaign_uid=60fbcde0d8691"><span class="material-icons-outlined me-2">copy_all</span> Copy</a></li><li><a class="dropdown-item list-action-single" link-method="POST" link-confirm="You're about to delete <span class=&quot;fw-600&quot;>asdasda</span> campaign. This can not be undone." href="https://ui.com/campaigns/delete?uids=60fbcde0d8691"><span class="material-icons-outlined me-2">delete_outline</span> Delete</a></li></ul></div></div></td></tr>
            </tbody></table>
        `);
    }

    masking() {
        var _this = this;

        _this.content.addClass('list-loading');
        _this.content.append('<span class="list-loading-msg">'+LOADING_WAIT+'</span>');

        placeholderLoading(_this.content);
    }

    unmasking() {
        var _this = this;
        
        _this.content.removeClass('list-loading');
        _this.content.find('.list-loading-msg').remove();

        removePlaceholderLoading(_this.content);
    }

    load() {
        var _this = this;

        if(_this.xhr && _this.xhr.readyState != 4){
            _this.xhr.abort();
        }

        _this.masking();

        _this.xhr = $.ajax({
            url: _this.url,
            method: _this.method,
            data: _this.data()
        }).done(function(response) {
            _this.content.html(response); 
            initJs(_this.content);

            // method
            if (typeof(_this.options.loaded) !== 'undefined') {
                _this.options.loaded();
            }

            _this.unmasking();
        }).fail(function(response) {

            _this.unmasking();
        });
    }
}