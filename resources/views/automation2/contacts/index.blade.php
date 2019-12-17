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
    <i class="material-icons">search</i>
    <input class="form-control mt-3" name="contact_keyword" placeholder='Enter to search contacts...' />
</div>
    
<div class="contacts_list ajax-list"></div>
    
<script>
    var listContact = new List( $('.contacts_list'), {
        url: '{{ action('Automation2Controller@contactsList', [
                'uid' => $automation->uid,
            ]) }}',
        data: function() {
                return {
                    keyword: $('[name=contact_keyword]').val(),
                };
            },
        per_page: 5,
    });		
    listContact.load();
    
    // filters
    $('[name=contact_keyword]').keyup(function() {
        listContact.load();
    });
</script>