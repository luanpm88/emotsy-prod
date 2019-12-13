@include('automation2._info')
				
@include('automation2._tabs', ['tab' => 'statistics', 'sub' => 'all_contacts'])
    
<div class="insight-topine d-flex small align-items-center">
    <div class="insight-desc mr-auto">
        There are 3 contacts who received `<span class="font-weight-semibold">Followup email</span>`
    </div>
    <div class="insight-action">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group btn-group-sm" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="#">Export this list</a>
                <a class="dropdown-item" href="#">Copy to a new list</a>
                <a class="dropdown-item" href="#">Tag those contacts</a>
              </div>
            </div>
        </div>
    </div>
</div>

<div class="input-with-icon mb-4">
    <i class="fa fa-search"></i>
    <input class="form-control mt-3" name="keyworf" placeholder='Enter to search contacts...' />
</div>
    
<p class="insight-intro mb-2 small">
    All contacts (4,600)
</p>
    
<div class="mc-table small border-top">
    <div class="mc-row d-flex align-items-center">
        <div class="media trigger">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                <img src="https://i.pravatar.cc/300" />
            </a>
        </div>
        <div class="flex-fill flex-grow-1" style="width: 50%">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                gheisa@gmail.com
            </a>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                GHei
            </label>
            <desc>First name</desc>
        </div>
    </div>
    <div class="mc-row d-flex align-items-center">
        <div class="media email">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                <i class="lnr lnr-user bg-primary"></i>
            </a>
        </div>
        <div class="flex-fill flex-grow-1" style="width: 50%">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                andoirde@lumiasoft.jp.co
            </a>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                iOsanti
            </label>
            <desc>First name</desc>
        </div>
    </div>
    <div class="mc-row d-flex align-items-center">
        <div class="media open">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                <img src="https://i.pravatar.cc/400" />
            </a>
        </div>
        <div class="flex-fill flex-grow-1" style="width: 50%">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                obama@america.us
            </a>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                Obama
            </label>
            <desc>First name</desc>
        </div>
    </div>
    <div class="mc-row d-flex align-items-center">
        <div class="media triggerr">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                <i class="lnr lnr-user bg-danger"></i>
            </a>
        </div>
        <div class="flex-fill flex-grow-1" style="width: 50%">
            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', $automation->uid) }}')" class="font-weight-semibold d-block">
                gheisa@gmail.com
            </a>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                GHei
            </label>
            <desc>First name</desc>
        </div>
    </div>
</div>
    
<pagination class="d-flex align-items-center mt-3">
    <label class="small mb-0 mr-auto">Page 1 of <span class="font-weight-semibold">3</span></label>
    <ul class="pagination justify-content-end small align-items-center mb-0">
        <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
        </li>
        <li class="page-item active" aria-current="page">
          <span class="page-link">
            1
            <span class="sr-only">(current)</span>
          </span>
        </li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#">Next</a>
        </li>
    </ul>
</pagination>