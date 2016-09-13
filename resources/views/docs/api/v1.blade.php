@extends('layouts.account')

@section('title', trans('messages.demo'))

@section('content')
  <div class="api-page">
      <h1>{{ trans('messages.API_Documentation') }}</h1>
        
        <p class="alert alert-info">{!! trans('messages.api_token_guide', ["link" => action("Api\MailListController@index", ["api_token" => "YOUR_API_TOKEN"])]) !!}</p>
      
      <h2>{{ trans('messages.LISTS') }}</h2>
      <table class="table table-box pml-table table-log">
        <tr>
          <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
          <th width="40%">{{ trans('messages.Endpoint') }}</th>
          <th>{{ trans('messages.Function') }}</th>
        </tr>
        
        <!-- LISTS -->
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists</a>
          </td>
          <td>
            Get information about all lists
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">                
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{id}</a>
          </td>
          <td>
            Get information about a specific list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$id</var></dt>
                  <dd>Campaign's id</dd>
                </dl></div>
                  
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
        
      </table>
        
      <h2>{{ trans('messages.CAMPAIGNS') }}</h2>
      <table class="table table-box pml-table table-log">
        <tr>
          <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
          <th width="40%">{{ trans('messages.Endpoint') }}</th>
          <th>{{ trans('messages.Function') }}</th>
        </tr>
        
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/campaigns</a>
          </td>
          <td>
            Get information about all campaigns
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">                
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/campaigns/{id}</a>
          </td>
          <td>
            Get information about a specific campaign
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$id</var></dt>
                  <dd>Campaign's id</dd>
                </dl></div>
        
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
        
      </table>
        
      <h2>{{ trans('messages.SUBSCRIBERS') }}</h2>
      <table class="table table-box pml-table table-log">
        <tr>
          <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
          <th width="40%">{{ trans('messages.Endpoint') }}</th>
          <th>{{ trans('messages.Function') }}</th>
        </tr>
        
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_id}/subscribers</a>
          </td>
          <td>
            Display all list's subscribers
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$list_id</var></dt>
                  <dd>List's id</dd>
                </dl></div>
        
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_id}/subscribers/{id}</a>
          </td>
          <td>
            Get information about a specific subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$list_id</var></dt>
                  <dd>List's id</dd>
                  <dt><var>$id</var></dt>
                  <dd>Subsciber's id</dd>
                </dl></div>
        
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_id}/subscribers/store</a>
          </td>
          <td>
            Create subscriber for a mail list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$request</var></dt>
                  <dd>All subscriber information: EMAIL (required), FIRST_NAME (?), LAST_NAME (?),... (depending on the list fields configuration)</dd>
                  <dt><var>$list_id</var></dt>
                  <dd>List's id</dd>
                </dl></div>
        
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_id}/subscribers/{id}/subscribe</a>
          </td>
          <td>
            Subscribe a subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$list_id</var></dt>
                  <dd>List's id</dd>
                  <dt><var>$id</var></dt>
                  <dd>Subsciber's id</dd>
                </dl></div>
        
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_id}/subscribers/{id}/unsubscribe</a>
          </td>
          <td>
            Unsubscribe a subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list"><dl>
                  <dt><var>$list_id</var></dt>
                  <dd>List's id</dd>
                  <dt><var>$id</var></dt>
                  <dd>Subsciber's id</dd>
                </dl></div>
        
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-danger">{{ trans('messages.DELETE') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_id}/subscribers/{id}/delete</a>
          </td>
          <td>
            Delete a subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$list_id</var></dt>
                    <dd>List's id</dd>
                    <dt><var>$id</var></dt>
                    <dd>Subsciber's id</dd>
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    Illuminate\Http\Response
                  </div>
              </div>
            </div>
          </td>
        </tr>
        
      </table>
  </div>
    
    
  <script>
    $(document).ready(function() {
      $(".toogle-api").click(function() {
        $(this).parents("tr").next().toggle();
      });
    });
  </script>
    
@endsection
