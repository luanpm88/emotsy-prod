<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class Website extends Model
{
    use HasFactory;
    use HasUid;

    const STATUS_INACTIVE = 'inactive';
    const STATUS_CONNECTED = 'connected';

    protected $fillable = [
        'url',
    ];

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public static function newDefault($customer)
    {
        $website = new self();
        $website->title = trans('messages.website.untitled');
        $website->status = self::STATUS_INACTIVE;
        $website->customer_id = $customer->id;

        return $website;
    }

    public function createFromArray($params)
    {
        $validator = \Validator::make($params, [
            'url' => 'required|active_url',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        // connect and get title
        try {
            $this->title = self::getTitleFromUrl($params['url']);
        } catch (\Exception $e) {
            $validator->after(function ($validator) use ($e) {
                $validator->errors()->add('url', $e->getMessage());
            });
        }

        if ($validator->fails()) {
            return $validator;
        }

        $this->fill($params);
        $this->save();

        return $validator;
    }

    public static function getTitleFromUrl($url)
    {
        $document = new \DOMDocument();
        $document->encoding = 'utf-8';
        $document->loadHTML(mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
        
        $tags = $document->getElementsByTagName('title');

        if (count($tags) == 0) {
            throw new \Exception(trans('messages.website.title.not_found'));
        }
        
        return $tags[0]->textContent;
    }

    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty($keyword)) {
            $query = $query->where('title', 'like', '%'.trim($keyword).'%');
        }
    }

    public function check()
    {
        $document = new \DOMDocument();
        $document->encoding = 'utf-8';
        $document->loadHTML(mb_convert_encoding(file_get_contents($this->url), 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR);
        
        $tag = $document->getElementById('AcelleConnectScript');

        if (!$tag) {
            throw new \Exception(trans('messages.website.can_not_find_connect_script'));
        }
    }

    public function connect()
    {
        $this->check();
        $this->status = self::STATUS_CONNECTED;
        $this->save();
    }

    public function disconnect()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }
}
