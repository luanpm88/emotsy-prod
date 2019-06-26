<div class="modal-header">    
    <h5 class="modal-title"><strong>{{ trans('messages.campaign.spam_score') }}</strong></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mc_section mb-0">
        <pre>{{ $campaign->score() }}</pre>
    </div>
</div>
<div class="modal-footer text-center">
    <a href="javascript:;" class="btn btn-primary bg-grey mr-10" data-dismiss="modal">{{ trans('messages.close') }}</a>
</div>
