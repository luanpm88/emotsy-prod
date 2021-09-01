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
                'keywords' => [],
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
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.templates'), trans('messages.upload')],
                'url' => action('TemplateController@uploadTemplate'),
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
                'keywords' => [],
            ],
            [
                'names' => [trans('messages.account'), trans('messages.activities')],
                'url' => action('AccountController@logs'),
                'keywords' => [
                    trans('messages.log'),
                ],
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

                        foreach ($item['names'] as $name) {
                            $name = trim(strtolower($name));
                            if (strpos($name, $keyword) !== false) {
                                $row['rate'] += 1;
                                $exist = true;
                            }
                        }

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
        sleep(1);
        
        return view('search.campaigns', [
            'keyword' => $request->keyword,
        ]);
    }
}
