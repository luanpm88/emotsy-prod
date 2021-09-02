<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Exception;

class SearchController extends Controller
{
    public function general(Request $request)
    {
        $items = [
            [
                'names' => [trans('messages.api')],
                'url' => action('AccountController@api'),
                'keywords' => [
                    trans('messages.api'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.profile')],
                'url' => action('AccountController@profile'),
                'keywords' => [
                    trans('messages.theme_mode'),
                    trans('messages.account.menu_layout'),
                    trans('messages.account.personality'),
                    trans('messages.profile_photo'),
                    trans('messages.basic_information'),
                    trans('messages.email'),
                    trans('messages.password'),
                    trans('messages.change_password'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.billing')],
                'url' => action('AccountController@billing'),
                'keywords' => [
                    trans('messages.payment_method'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.subscription')],
                'url' => action('SubscriptionController@index'),
                'keywords' => [
                    trans('messages.subscription.resume'),
                    trans('messages.subscription.cancel_now'),
                    trans('messages.subscription.change_plan'), 
                    trans('messages.invoices'),
                    trans('messages.transactions'),
                    trans('messages.subscription.logs'),
                    trans('messages.payment_method'),
                ],
            ],
            [
                'names' => [trans('messages.dashboard')],
                'url' => action('MailListController@index'),
                'keywords' => [
                    trans('messages.home'),
                ],
            ],
            [
                'names' => [trans('messages.lists')],
                'url' => action('MailListController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.campaigns')],
                'url' => action('CampaignController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.campaigns'), trans('messages.create')],
                'url' => action('CampaignController@selectType'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.automations')],
                'url' => action('Automation2Controller@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates')],
                'url' => action('TemplateController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.create')],
                'url' => action('TemplateController@builderCreate'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.upload')],
                'url' => action('TemplateController@uploadTemplate'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.gallery')],
                'url' => action('TemplateController@index', [
                    'view' => 'grid',
                    'from' => 'gallery',
                ]),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.products')],
                'url' => action('ProductController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.stores_connections')],
                'url' => action('SourceController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.stores_connections'), trans('messages.source.add_new')],
                'url' => action('SourceController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.activities')],
                'url' => action('AccountController@logs'),
                'keywords' => [
                    trans('messages.log'),
                ],
            ],
            [
                'names' => [trans('messages.sending_domains')],
                'url' => action('SendingDomainController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.sending_domains'), trans('messages.add')],
                'url' => action('SendingDomainController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.tracking_domains')],
                'url' => action('TrackingDomainController@index'),
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.tracking_domains'), trans('messages.add')],
                'url' => action('TrackingDomainController@create'),
                'keywords' => [
                    trans('messages.new'),
                    trans('messages.add'),
                ],
            ],
            [
                'names' => [trans('messages.blacklist')],
                'url' => action('BlacklistController@index'),
                'keywords' => [],
            ],
        ];

        // search
        $results = [];
        foreach ($items as $item) {
            $row = [
                'rate' => 0,
                'item' => $item,
            ];

            if (isset($request->keyword)) {
                $keyword = trim(strtolower($request->keyword));

                // Keywords
                if (!empty($keyword)) {
                    $keywords = preg_split('/\s+/', $keyword);
                    $allExist = true;

                    foreach ($keywords as $keyword) {
                        $exist = false;

                        // search by names
                        foreach ($item['names'] as $name) {
                            $name = trim(strtolower($name));
                            if (strpos($name, $keyword) !== false) {
                                $row['rate'] += 1;
                                $exist = true;
                            }
                        }

                        // search by keywords
                        if (isset($item['keywords'])) {
                            foreach ($item['keywords'] as $k) {
                                $k = trim(strtolower($k));
                                if (strpos($k, $keyword) !== false) {
                                    $row['rate'] += 1;
                                    $exist = true;
                                }
                            }
                        }

                        if (!$exist) {
                            $allExist = false;
                        }
                    }

                    if (!$allExist) {
                        $row['rate'] = 0;
                    }
                }
            }

            if ($row['rate'] > 0) {
                $results[] = $row;
            }
        }

        // sort
        usort($results, function($a, $b) {
            if ($a['rate'] != $b['rate']) {
                return $a['rate'] < $b['rate'];
            } else {
                return strcmp(implode(' ', $a['item']['names']), implode(' ', $b['item']['names']));
            }
        });

        return view('search.general', [
            'keyword' => $request->keyword,
            'results' => $results,
        ]);
    }
    
    public function campaigns(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $campaigns = $customer->campaigns()
            ->search($request->keyword)
            ->filter($request)
            ->orderBy('campaigns.name', 'asc')
            ->paginate($count);
        
        return view('search.campaigns', [
            'keyword' => $request->keyword,
            'campaigns' => $campaigns,
        ]);
    }

    public function lists(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $lists = $customer->mailLists()
            ->search($request->keyword)
            ->orderBy('mail_lists.name', 'asc')
            ->paginate($count);
        
        return view('search.lists', [
            'keyword' => $request->keyword,
            'lists' => $lists,
        ]);
    }

    public function automations(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $automations = $customer->automation2s()
            ->search($request->keyword)
            ->orderBy('automation2s.name', 'asc')
            ->paginate($count);
        
        return view('search.automations', [
            'keyword' => $request->keyword,
            'automations' => $automations,
        ]);
    }

    public function templates(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $templates = $customer->templates()
            ->search($request->keyword)
            ->orderBy('templates.name', 'asc')
            ->paginate($count);
        
        return view('search.templates', [
            'keyword' => $request->keyword,
            'templates' => $templates,
        ]);
    }

    public function subscribers(Request $request)
    {
        $customer = $request->user()->customer;
        $count = 5;

        $subscribers = $customer->subscribers()
            ->search($request->keyword)
            ->paginate($count);
        
        return view('search.subscribers', [
            'keyword' => $request->keyword,
            'subscribers' => $subscribers,
        ]);
    }
}
