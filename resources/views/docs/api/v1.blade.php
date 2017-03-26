@extends('layouts.account')

@section('title', trans('messages.API_Documentation'))

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
            <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists</a>
          </td>
          <td>
            Add a new list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td>
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$name</var></dt></dt>
                    <dd>List's name</dd>
                    
                    <dt><var>$from_email</var></dt></dt>
                    <dd>Default From email address</dd>
                    
                    <dt><var>$from_name</var></dt></dt>
                    <dd>Default From name</dd>
                    
                    <dt><var>$default_subject &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt></dt>
                    <dd>Default email subject</dd>
                    <br/>
                    <h5>Contact information</h5>
                    
                    <dt><var>$contact[company]</var></dt></dt>
                    <dd>Name</dd>
                    
                    <dt><var>$contact[state]</var></dt></dt>
                    <dd>State / Province / Region</dd>
                        
                    <dt><var>$contact[address_1]</var></dt></dt>
                    <dd>Address 1</dd>
                    
                    <dt><var>$contact[address_2]</var></dt></dt>
                    <dd>Address 2</dd>
                    
                    <dt><var>$contact[city]</var></dt></dt>
                    <dd>City</dd>
                    
                    <dt><var>$contact[zip]</var></dt></dt>
                    <dd>Zip / Postal code</dd>
                    
                    <dt><var>$contact[phone]</var></dt></dt>
                    <dd>Phone</dd>
                    
                    <dt><var>$contact[country_id]</var></dt></dt>
                    <dd>Country</dd>
                    
                    <dt><var>$contact[email]</var></dt></dt>
                    <dd>Email</dd>
                    
                    <dt><var>$contact[url] &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt></dt>
                    <dd>Home page</dd>
                        
                    <dt><var>$subscribe_confirmation</var></dt></dt>
                    <dd>Send subscription confirmation email (Double Opt-In)</dd>
                        
                    <dt><var>$send_welcome_email</var></dt></dt>
                    <dd>Send a final welcome email</dd>
                        
                    <dt><var>$unsubscribe_notification</var></dt></dt>
                    <dd>Send unsubscribe notification to subscribers</dd>
                </dl></div>
                <h4>Returns</h4>
                <div class="list">
                    Creation messages in json
                </div>
              </div>
            </div>
          </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X POST -H "accept:application/json" -G \
{{ action("Api\MailListController@store") }}? \
-d api_token={{ Auth::user()->api_token }} \
-d name=List+1 \
-d from_email=admin@abccorp.org \
-d from_name=ABC+Corp. \
-d default_subject=Welcome+to+ABC+Corp. \
-d contact[company]=ABC+Corp. \
-d contact[state]=Armagh \
-d contact[address_1]=14+Tottenham+Court+Road+London+England \
-d contact[address_2]=44-46+Morningside+Road+Edinburgh+Scotland+EH10+4BF \
-d contact[city]=Noname \
-d contact[zip]=80000 \
-d contact[phone]=123+456+889 \
-d contact[country_id]={{ \Acelle\Model\Country::first()->id }} \
-d contact[email]=info@abccorp.org \
-d contact[url]=http://www.abccorp.org \
-d subscribe_confirmation=1 \
-d send_welcome_email=1 \
-d unsubscribe_notification=1</code></pre>
            </td>
        </tr>
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
          <td>
            <div>
              <div class="description detailed">                
                <h4>Returns</h4>
                <div class="list">
                  List of all user's mail lists in json
                </div>
              </div>
            </div>
          </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{{ action("Api\MailListController@index") }}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{uid}</a>
          </td>
          <td>
            Get information about a specific list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td>
            <div>
              <div class="description detailed">
                <h4>Returns</h4>
                <div class="list">
                  All list informations in json
                </div>
              </div>
            </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", action("Api\MailListController@show", "-ID-")) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
            
        <!--<tr>
          <td>
            <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists</a>
          </td>
          <td>
            Create a new list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td colspan="2">
            <div>
              <div class="description detailed">
                <h4>Parameters</h4>
                <div class="list">
                    <dl>
                      <dt><var>$name</var> <small class="api-required">required</small></dt>
                      <dd>List's name</dd>
                    </dl>
                    <dl>
                      <dt><var>$from_email</var></dt>
                      <dd>From email</dd>
                    </dl>
                    <dl>
                      <dt><var>$from_name</var></dt>
                      <dd>From name</dd>
                    </dl>
                    <dl>
                      <dt><var>$contact[address_1]</var></dt>
                      <dd>List contact's address 1</dd>
                    </dl>
                    <dl>
                      <dt><var>$contact[address_2]</var></dt>
                      <dd>List contact's address 2</dd>
                    </dl>
                </div>
                    
                <h4>Returns</h4>
                <div class="list">
                  Illuminate\Http\Response
                </div>
              </div>
            </div>
          </td>
        </tr>-->
        
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
            <td>
              <div>
                <div class="description detailed">                
                  <h4>Returns</h4>
                  <div class="list">
                    List of all user's campaigns in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{{ action("Api\CampaignController@index") }}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/campaigns/{uid}</a>
          </td>
          <td>
            Get information about a specific campaign
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Returns</h4>
                  <div class="list">
                    All campaign overview informations in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", action("Api\CampaignController@show", "-ID-")) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
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
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers</a>
          </td>
          <td>
            Display list's subscribers
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                    <div class="list"><dl>
                        <dt><var>$list_uid</var></dt>
                        <dd>List's uid</dd>
                        <dt><var>$per_page &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</var></dt>
                        <dd>Number of subscribers per page</dd>
                        <dt><var>$page &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }} - default: 25</var></dt>
                        <dd>Page number</dd>                    
                    </dl></div>
                    <h4>Returns</h4>
                    <div class="list">
                      List of list's subscribers in json
                    </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{!! str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@index", ["list_id" => "-LIST_ID-"])) !!}? \
-d api_token={{ Auth::user()->api_token }} \
-d per_page=20 \
-d page=2
</code></pre>
            </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers/{uid}</a>
          </td>
          <td>
            Get information about a specific subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$uid</var></dt>
                    <dd>Subsciber's uid or email</dd>                   
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    All subscriber information in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@show", ["list_id" => "-LIST_ID-",  "id" => "-ID-"]))) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
            
        
        <tr>
          <td>
            <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/subscribers/{email}</a>
          </td>
          <td>
            Find subscribers with email
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$email</var></dt>
                    <dd>Subsciber's email</dd>                   
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    All subscribers with the email
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{email}</redbold>", str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@showByEmail", ["email" => "-ID-"]))) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers/store</a>
          </td>
          <td>
            Create subscriber for a mail list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$EMAIL</var></dt></dt>
                    <dd>Subscriber's email</dd>
                    <dt><var>$[OTHER_FIELDS...]</var></dt></dt>
                    <dd>All subscriber's other fields: FIRST_NAME (?), LAST_NAME (?),... (depending on the list fields configuration)</dd>
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    Creation messages in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X POST -H "accept:application/json" -G \
{!! str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@store", ["list_id" => "-LIST_ID-"])) !!}? \
-d api_token={{ Auth::user()->api_token }} \
-d EMAIL=test@gmail.com \
-d FIRST_NAME=Marine \
-d LAST_NAME=Joze
</code></pre>
            </td>
        </tr>
            
        
        <tr>
          <td>
            <span class="label label-flat bg-success">{{ trans('messages.PATCH') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers/update</a>
          </td>
          <td>
            Update subscriber for a mail list
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$EMAIL</var></dt></dt>
                    <dd>Subscriber's email</dd>
                    <dt><var>$[OTHER_FIELDS...]</var></dt></dt>
                    <dd>All subscriber's other fields: FIRST_NAME (?), LAST_NAME (?),... (depending on the list fields configuration)</dd>
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    Update messages in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X PATCH -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@update", ["list_uid" => "-LIST_ID-",  "uid" => "-ID-"]))) !!}? \
-d api_token={{ Auth::user()->api_token }} \
-d EMAIL=test@gmail.com \
-d FIRST_NAME=Marine \
-d LAST_NAME=Joze
</code></pre>
            </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-success">{{ trans('messages.PATCH') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers/{uid}/subscribe</a>
          </td>
          <td>
            Subscribe a subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$uid</var></dt>
                    <dd>Subsciber's uid</dd>
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    Result messages in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X PATCH -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@subscribe", ["list_id" => "-LIST_ID-",  "id" => "-ID-"]))) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-success">{{ trans('messages.PATCH') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers/{uid}/unsubscribe</a>
          </td>
          <td>
            Unsubscribe a subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$uid</var></dt>
                    <dd>Subsciber's uid</dd>
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    Result messages in json
                  </div>
                </div>
              </div>
            </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X PATCH -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", str_replace("-LIST_ID-", "<redbold>{list_id}</redbold>", action("Api\SubscriberController@unsubscribe", ["list_id" => "-LIST_ID-",  "id" => "-ID-"]))) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
          
          
        <tr>
          <td>
            <span class="label label-flat bg-danger">{{ trans('messages.DELETE') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/lists/{list_uid}/subscribers/{uid}/delete</a>
          </td>
          <td>
            Delete a subscriber
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td>
            <div>
              <div class="description detailed">
                  <h4>Parameters</h4>
                  <div class="list"><dl>
                    <dt><var>$uid</var></dt>
                    <dd>Subsciber's uid</dd>
                  </dl></div>
          
                  <h4>Returns</h4>
                  <div class="list">
                    Result messages in json
                  </div>
              </div>
            </div>
          </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X DELETE -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", str_replace("-LIST_ID-", "<redbold>{list_uid}</redbold>", action("Api\SubscriberController@delete", ["list_id" => "-LIST_ID-",  "id" => "-ID-"]))) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
            </td>
        </tr>
        
      </table>
        
      
      <h2>{{ trans('messages.USERS') }}</h2>
      <table class="table table-box pml-table table-log">
        <tr>
          <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
          <th width="40%">{{ trans('messages.Endpoint') }}</th>
          <th>{{ trans('messages.Function') }}</th>
        </tr>
        
        <!-- USER -->
        <tr>
          <td>
            <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/users</a>
          </td>
          <td>
            Create a new user
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td>
            <h4>Parameters</h4>
            <div class="list"><dl>
              <dt><var>$user_group_id</var></dt>
              <dd>User group id</dd>
              <dt><var>$email</var></dt>
              <dd>User's email</dd>
              <dt><var>$first_name</var></dt>
              <dd>User's first name</dd>
              <dt><var>$last_name</var></dt>
              <dd>User's last name</dd>
              <dt><var>$timezone</var></dt>
              <dd>User' timezone</dd>
              <dt><var>$language_id</var></dt>
              <dd>Language id</dd>
              <dt><var>$image &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User's avatar</dd>
            </dl></div>
              
            <div>
              <div class="description detailed">                
                <h4>Returns</h4>
                <div class="list">
                  Result message and user's api token in json
                </div>
              </div>
            </div>
          </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X POST -H "accept:application/json" -G \
{{ action("Api\UserController@store") }}? \
-d api_token={{ Auth::user()->api_token }} \
-d user_group_id=1 \
-d email=user_name@gmail.com \
-d first_name=Luan \
-d last_name=Pham \
-d timezone=America/Godthab \
-d language_id=1 \
-d password=123456
</code></pre>
            </td>
        </tr>
          
        <tr>
          <td>
            <span class="label label-flat bg-success">{{ trans('messages.PATCH') }}</span>
          </td>
          <td>
            <a href="#more" class="toogle-api">/api/v1/users/{uid}</a>
          </td>
          <td>
            Update user information
          </td>
        </tr>
        <tr style="display:none;background: #f6f6f6">
          <td></td>
          <td>
            <h4>Parameters</h4>
            <div class="list"><dl>
              <dt><var>$user_group_id &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User group id</dd>
              <dt><var>$email &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User's email</dd>
              <dt><var>$first_name &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User's first name</dd>
              <dt><var>$last_name &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User's last name</dd>
              <dt><var>$timezone &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User' timezone</dd>
              <dt><var>$language_id &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>Language id</dd>
              <dt><var>$image &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}</span></var></dt>
              <dd>User's avatar</dd>
            </dl></div>
              
            <div>
              <div class="description detailed">                
                <h4>Returns</h4>
                <div class="list">
                  Result message and user's api token in json
                </div>
              </div>
            </div>
          </td>
            <td>
                <h4>Example:</h4>
                <pre class=""><code>curl -X PATCH -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{uid}</redbold>", action("Api\UserController@update", ['id' => "-ID-"])) !!}? \
-d api_token={{ Auth::user()->api_token }} \
-d user_group_id=1 \
-d email=user_name@gmail.com \
-d first_name=Luan \
-d last_name=Pham \
-d timezone=America/Godthab \
-d language_id=1 \
-d password=123456
</code></pre>
            </td>
        </tr>
      </table>
        
        
        
        <h2>{{ trans('messages.USER_GROUPS') }}</h2>
        <table class="table table-box pml-table table-log">
          <tr>
            <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
            <th width="40%">{{ trans('messages.Endpoint') }}</th>
            <th>{{ trans('messages.Function') }}</th>
          </tr>
          
          <!-- USER GROUPS -->
          
          <tr>
            <td>
              <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/user_groups</a>
            </td>
            <td>
              Get information about all user groups
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">                
                  <h4>Returns</h4>
                  <div class="list">
                    List of all user groups in json
                  </div>
                </div>
              </div>
            </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{{ action("Api\UserGroupController@index") }}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
              </td>
          </tr>
            
            
          <tr>
            <td>
              <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/user_groups/{id}</a>
            </td>
            <td>
              Get information about a specific user group
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                    <div class="list"><dl>
                      <dt><var>$id</var></dt></dt>
                      <dd>User group's id</dd>
                    </dl></div>
                  <h4>Returns</h4>
                  <div class="list">
                    All user group informations in json
                  </div>
                </div>
              </div>
              </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{id}</redbold>", action("Api\UserGroupController@show", "-ID-")) !!}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
              </td>
          </tr>
          
          
          <tr>
            <td>
              <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/user_groups</a>
            </td>
            <td>
              Add a new user group
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                    <div class="list"><dl>
                      <dt><var>$name</var></dt></dt>
                      <dd>User group's name</dd>
                      
                      <dt><var>$frontend_access</var></dt></dt>
                      <dd>Is users in the group can access campaign view</dd>
                        
                      <dt><var>$backend_access</var></dt></dt>
                      <dd>Is users in the group can access admin view</dd>
                        
                      <dt><var>$options[frontend][OPTION...]</var></dt></dt>
                      <dd>Set campaign view options. OPTION is the option you want set value. All other options will be set to system default values.</dd>
                    
                      <dt><var>$options[backend][OPTION...]</var></dt></dt>
                      <dd>Set admin view permissions. OPTION is the option you want set value. All other options will be set to system default values.</dd>
                        
                      <dt><var>$sending_servers[SENDING_SERVER_UID][FITNESS]</var></dt></dt>
                      <dd>Add sending servers to the group. This only works if <span class="text-semibold">$options[frontent][all_sending_servers]</span> is set to <span class="text-semibold">0</span>.</dd>
                  </dl></div>
                  <h4>Returns</h4>
                  <div class="list">
                      Creation messages in json
                  </div>
                </div>
              </div>
            </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X POST -H "accept:application/json" -G \
{{ action("Api\UserGroupController@store") }}? \
-d api_token={{ Auth::user()->api_token }} \
-d name=Group+A \
-d frontend_access=1 \
-d backend_access=1 \
-d options[frontend][list_max]=2 \
-d options[frontend][max_size_upload_total]=200 \
-d options[frontend][all_sending_servers]=0 \
-d sending_servers[<redbold>{sending_server_uid}</redbold>][fitness]=40 \
-d sending_servers[<redbold>{sending_server_uid}</redbold>][fitness]=60</code></pre>
              </td>
          </tr>
          
            
          <tr>
            <td>
              <span class="label label-flat bg-success">{{ trans('messages.PATCH') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/user_groups/{id}</a>
            </td>
            <td>
              Update user group
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                    <div class="list"><dl>
                      <dt><var>$name</var></dt></dt>
                      <dd>User group's name</dd>
                      
                      <dt><var>$frontend_access</var></dt></dt>
                      <dd>Is users in the group can access campaign view</dd>
                        
                      <dt><var>$backend_access</var></dt></dt>
                      <dd>Is users in the group can access admin view</dd>
                        
                      <dt><var>$options[frontend][OPTION...]</var></dt></dt>
                      <dd>Set campaign view options. OPTION is the option you want set value. All other options will be set to system default values.</dd>
                    
                      <dt><var>$options[backend][OPTION...]</var></dt></dt>
                      <dd>Set admin view permissions. OPTION is the option you want set value. All other options will be set to system default values.</dd>
                        
                      <dt><var>$sending_servers[SENDING_SERVER_UID][FITNESS]</var></dt></dt>
                      <dd>Add sending servers to the group. This only works if <span class="text-semibold">$options[frontent][all_sending_servers]</span> is set to <span class="text-semibold">0</span>.</dd>
                  </dl></div>
                  <h4>Returns</h4>
                  <div class="list">
                      Updating result in json
                  </div>
                </div>
              </div>
            </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X PATCH -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{id}</redbold>", action("Api\UserGroupController@update", "-ID-")) !!}? \
-d api_token={{ Auth::user()->api_token }} \
-d name=Group+A \
-d frontend_access=1 \
-d backend_access=1 \
-d options[frontend][list_max]=2 \
-d options[frontend][max_size_upload_total]=200 \
-d options[frontend][all_sending_servers]=0 \
-d sending_servers[<redbold>{sending_server_uid}</redbold>][fitness]=40 \
-d sending_servers[<redbold>{sending_server_uid}</redbold>][fitness]=60</code></pre>
              </td>
          </tr>
            
            
          <tr>
            <td>
              <span class="label label-flat bg-danger">{{ trans('messages.DELETE') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/user_groups/{id}</a>
            </td>
            <td>
              Delete user group
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                  <h4>Parameters</h4>
                    <div class="list"><dl>
                      <dt><var>$id</var></dt></dt>
                      <dd>User group's id</dd>                      
                  </dl></div>
                  <h4>Returns</h4>
                  <div class="list">
                      Deleting result in json
                  </div>
                </div>
              </div>
            </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X DELETE -H "accept:application/json" -G \
{!! str_replace("-ID-", "<redbold>{id}</redbold>", action("Api\UserGroupController@update", "-ID-")) !!}? \
-d api_token={{ Auth::user()->api_token }}</code></pre>
              </td>
          </tr>
          
        </table>
            
            
        <h2>{{ trans('messages.SENDING_SERVERS') }}</h2>
        <table class="table table-box pml-table table-log">
          <tr>
            <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
            <th width="40%">{{ trans('messages.Endpoint') }}</th>
            <th>{{ trans('messages.Function') }}</th>
          </tr>
          
          <!-- SENDING SERVERS -->          
          <tr>
            <td>
              <span class="label label-flat bg-info">{{ trans('messages.GET') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/sending_servers</a>
            </td>
            <td>
              Get information about all sending servers
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">                
                  <h4>Returns</h4>
                  <div class="list">
                    List of all sending servers in json
                  </div>
                </div>
              </div>
            </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X GET -H "accept:application/json" -G \
{{ action("Api\SendingServerController@index") }}? \
-d api_token={{ Auth::user()->api_token }}
</code></pre>
              </td>
          </tr>
        </table>
            
        <h2>{{ trans('messages.file') }}</h2>
        <table class="table table-box pml-table table-log">
          <tr>
            <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
            <th width="40%">{{ trans('messages.Endpoint') }}</th>
            <th>{{ trans('messages.Function') }}</th>
          </tr>
          
          <!-- SENDING SERVERS -->          
          <tr>
            <td>
              <span class="label label-flat bg-primary">{{ trans('messages.POST') }}</span>
            </td>
            <td>
              <a href="#more" class="toogle-api">/api/v1/file/upload</a>
            </td>
            <td>
                Upload a file to customer's storage
            </td>
          </tr>
          <tr style="display:none;background: #f6f6f6">
            <td></td>
            <td>
              <div>
                <div class="description detailed">
                <h4>Parameters</h4>
                    <div class="list"><dl>
                      <dt><var>$file_url</var></dt></dt>
                      <dd>File url</dd>
                        <dt><var>$subdirectory &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }} - Default: user root directory</span></var></dt></dt>
                      <dd>Custom subdirectory</dd>           
                  </dl></div>
                  <h4>Returns</h4>
                  <div class="list">
                        Upload result message
                  </div>
                </div>
              </div>
            </td>
              <td>
                  <h4>Example:</h4>
                  <pre class=""><code>curl -X POST -H "accept:application/json" -G \
{{ action("Api\FileController@upload") }}? \
-d api_token={{ Auth::user()->api_token }} \
-d file_url=http://demo.acellemail.com/images/logo_big.png \
-d subdirectory='path/to/file'
</code></pre>
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
