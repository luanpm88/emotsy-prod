<?php

/**
 * Template class.
 *
 * Model class for template
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Validator;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Acelle\Library\Tool;
use Acelle\Library\Rss;
use Acelle\Library\Storage\Contracts\Storable;
use Illuminate\Validation\ValidationException;
use KubAT\PhpSimple\HtmlDomParser;

class Template extends Model implements Storable
{
    const STORAGE_DIR = 'templates';

    const CATEGORY_WOO = 'woo';
    const CATEGORY_BASIC = 'basic';
    const CATEGORY_FEATURED = 'featured';
    const CATEGORY_THEME = 'theme';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'content',
    ];

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating item.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            $item->uid = $uid;
        });
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function admin()
    {
        return $this->belongsTo('Acelle\Model\Admin');
    }

    /**
     * The template that belong to the categories.
     */
    public function categories()
    {
        return $this->belongsToMany('Acelle\Model\TemplateCategory', 'templates_categories', 'template_id', 'category_id');
    }

    /**
     * Search.
     *
     * @return collect
     */
    public static function scopeFilter($query, $request)
    {
        // Category
        if ($request->category_uid) {
            $category = \Acelle\Model\TemplateCategory::findByUid($request->category_uid);

            $query = $query->whereHas('categories', function ($q) use ($category) {
                $q->whereIn('template_categories.id', [$category->id]);
            })->get();
        }
    }

    /**
     * Search.
     *
     * @return collect
     */
    public static function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%'.trim($keyword).'%');
        }
    }

    /**
     * Customer templates.
     *
     * @return collect
     */
    public static function scopeCustom($query)
    {
        $query = $query->where('customer_id', '!=', null);
    }

    /**
     * Public/Gallery templates.
     *
     * @return collect
     */
    public static function scopeShared($query)
    {
        $query = $query->where('customer_id', '=', null);
    }

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Template tags.
     *
     * All availabel template tags
     */
    public static function tags($list = null)
    {
        $tags = [];

        $tags[] = ['name' => 'SUBSCRIBER_EMAIL', 'required' => false];

        // List field tags
        if (isset($list)) {
            foreach ($list->fields as $field) {
                if ($field->tag != 'EMAIL') {
                    $tags[] = ['name' => 'SUBSCRIBER_'.$field->tag, 'required' => false];
                }
            }
        }

        $tags = array_merge($tags, [
            ['name' => 'UNSUBSCRIBE_URL', 'required' => false],
            ['name' => 'SUBSCRIBER_UID', 'required' => false],
            ['name' => 'WEB_VIEW_URL', 'required' => false],
            ['name' => 'CAMPAIGN_NAME', 'required' => false],
            ['name' => 'CAMPAIGN_UID', 'required' => false],
            ['name' => 'CAMPAIGN_SUBJECT', 'required' => false],
            ['name' => 'CAMPAIGN_FROM_EMAIL', 'required' => false],
            ['name' => 'CAMPAIGN_FROM_NAME', 'required' => false],
            ['name' => 'CAMPAIGN_REPLY_TO', 'required' => false],
            ['name' => 'CURRENT_YEAR', 'required' => false],
            ['name' => 'CURRENT_MONTH', 'required' => false],
            ['name' => 'CURRENT_DAY', 'required' => false],
            ['name' => 'CONTACT_NAME', 'required' => false],
            ['name' => 'CONTACT_COUNTRY', 'required' => false],
            ['name' => 'CONTACT_STATE', 'required' => false],
            ['name' => 'CONTACT_CITY', 'required' => false],
            ['name' => 'CONTACT_ADDRESS_1', 'required' => false],
            ['name' => 'CONTACT_ADDRESS_2', 'required' => false],
            ['name' => 'CONTACT_PHONE', 'required' => false],
            ['name' => 'CONTACT_URL', 'required' => false],
            ['name' => 'CONTACT_EMAIL', 'required' => false],
            ['name' => 'LIST_NAME', 'required' => false],
            ['name' => 'LIST_SUBJECT', 'required' => false],
            ['name' => 'LIST_FROM_NAME', 'required' => false],
            ['name' => 'LIST_FROM_EMAIL', 'required' => false],
        ]);

        return $tags;
    }

    /**
     * Replace url from content.
     */
    public function replaceHtmlUrl($template_path)
    {
        $this->content = \Acelle\Library\Tool::replaceTemplateUrl($this->content, $template_path);
    }

    /**
     * Display creator name.
     *
     * @return string
     */
    public function displayCreatorName()
    {
        return is_object($this->admin) ? $this->admin->user->displayName() : (is_object($this->customer) ? $this->customer->user->displayName() : '');
    }

    /**
     * Contain category
     *
     * @return void
     */
    public function hasCategory($category)
    {
        return $this->categories()->where('template_categories.id', $category->id)->exists();
    }

    /**
     * Add category
     *
     * @return void
     */
    public function addCategory($category)
    {
        if (!$this->hasCategory($category)) {
            $this->categories()->attach($category->id);
        }
    }

    /**
     * Remove category
     *
     * @return void
     */
    public function removeCategory($category)
    {
        if ($this->hasCategory($category)) {
            $this->categories()->detach($category->id);
        }
    }

    /**
     * Copy new template.
     */
    public function copy($attributes=[])
    {
        $copy = $this->replicate();
        if (isset($attributes['name'])) {
            $copy->name = $attributes['name'];
        }
        $copy->created_at = \Carbon\Carbon::now();
        $copy->updated_at = \Carbon\Carbon::now();
        if (isset($attributes['customer_id'])) {
            $copy->admin_id = null;
            $copy->customer_id = $attributes['customer_id'];
        }
        if (isset($attributes['admin_id'])) {
            $copy->admin_id = $attributes['admin_id'];
            $copy->customer_id = null;
        }
        $copy->save();

        // categories
        foreach ($this->categories as $category) {
            $copy->addCategory($category);
        }

        // Copy uploaded folder
        if (file_exists($this->getStoragePath())) {
            if (!file_exists($copy->getStoragePath())) {
                mkdir($copy->getStoragePath(), 0777, true);
            }

            \Acelle\Library\Tool::xcopy($this->getStoragePath(), $copy->getStoragePath());
        }

        return $copy;
    }

    /**
     * Load from directory.
     */
    public function loadFromDirectory($tmp_path)
    {
        // try to find the main file, index.html | index.html | file_name.html | ...
        $main_file = null;
        $thumb = null;
        $sub_path = '';

        // find index
        $possible_main_file_names = array('index.html', 'index.htm');
        foreach ($possible_main_file_names as $name) {
            if (is_file($file = $tmp_path.'/'.$name)) {
                $main_file = $file;
                break;
            }
        }
        // if not find any first html file
        if ($main_file === null) {
            $objects = scandir($tmp_path);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (!is_dir($tmp_path.'/'.$object)) {
                        if (preg_match('/\.html?$/i', $object)) {
                            $main_file = $tmp_path.'/'.$object;
                            break;
                        }
                    }
                }
            }
        }

        // can not find main file
        if ($main_file === null) {
            $validator = Validator::make(['file' => ''], []);
            $validator->errors()->add('file', 'Cannot find main HTML file');
            throw new ValidationException($validator);
        }

        // read main file content
        $html_content = trim(file_get_contents($main_file));
        $this->content = $html_content;
        $this->untransform();
        $this->save();

        // upload path
        $upload_path = $this->getStoragePath();

        // copy all folder to upload path
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        // exec("cp -r {$tmp_path}/* {$public_upload_path}/");
        Tool::xcopy($tmp_path, $upload_path);
    }

    /**
     * Upload a template.
     */
    public static function uploadSystemTemplate($request)
    {
        return self::uploadTemplate($request, true);
    }

    /**
     * Upload a template.
     */
    public static function uploadTemplate($request, $asAdmin = false)
    {
        $user = $request->user();

        $rules = array(
            'file' => 'required|mimetypes:application/zip,application/octet-stream,application/x-zip-compressed,multipart/x-zip',
            'name' => 'required',
        );

        $validator = Validator::make($request->all(), $rules, [
            'file.mimetypes' => 'Input must be a valid .zip file',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // move file to temp place
        $tmp_path = storage_path('tmp/uploaded_template_'.$user->id.'_'.time());
        $file_name = $request->file('file')->getClientOriginalName();
        $request->file('file')->move($tmp_path, $file_name);
        $tmp_zip = join_paths($tmp_path, $file_name);

        // read zip file check if zip archive invalid
        $zip = new ZipArchive();
        if ($zip->open($tmp_zip, ZipArchive::CREATE) !== true) {
            // @todo hack
            // $validator = Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('file', 'Cannot open .zip file');
            throw new ValidationException($validator);
        }

        // unzip template archive and remove zip file
        $zip->extractTo($tmp_path);
        $zip->close();
        unlink($tmp_zip);

        // Save new template
        $template = new self();
        $template->source = self::SOURCE_UPLOADED;

        if ($asAdmin) {
            $template->admin_id = $user->admin->id;
        } else {
            $template->customer_id = $user->customer->id;
        }

        $template->fill($request->all());

        // UID is critically required by untransform()
        $template->uid = uniqid();
        $template->untransform();

        $template->save();

        $template->loadFromDirectory($tmp_path);

        // remove tmp folder
        // exec("rm -r {$tmp_path}");
        Tool::xdelete($tmp_path);

        return $template;
    }

    public function getArchivePath() : string
    {
        return join_paths('users', $this->getPath().'.zip');
    }

    public function toZip() : string
    {
        // Get real path for our folder
        $rootPath = $this->getStoragePath();
        $outputPath = join_paths('/tmp/', $this->uid.'.zip');

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        return $outputPath;
    }

    /**
     * Get public campaign upload dir.
     */
    public function getStoragePath($path = '/')
    {
        if ($this->customer) {
            // storage/app/users/{uid}/templates
            $base = $this->customer->user->getStoragePath(self::STORAGE_DIR);
        } else {
            // storage/app/users/admin/templates
            // IMPORTANT: templates are created from migration without associating with an admin
            $base = $this->getAdminStoragePath(self::STORAGE_DIR);
        }

        $base = join_paths($base, $this->uid);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getAdminStoragePath($path = '')
    {
        $base = storage_path('app/system');

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    /**
     * Get thumb.
     */
    public function getThumbName()
    {
        // find index
        $names = array('thumbnail.png', 'thumbnail.jpg', 'thumbnail.png', 'thumb.jpg', 'thumb.png');
        foreach ($names as $name) {
            if (is_file($file = $this->getStoragePath().$name)) {
                return $name;
            }
        }

        return;
    }

    /**
     * Get thumb.
     */
    public function getThumb()
    {
        // find index
        if ($this->getThumbName()) {
            return $this->getStoragePath().$this->getThumbName();
        }

        return;
    }

    /**
     * Get thumb.
     */
    public function getThumbUrl()
    {
        // @todo workaround
        // to avoid exception thrown by route(template_assets)
        if (is_null($this->uid)) {
            return;
        }

        if ($this->getThumbName()) {
            return route('template_assets', ['uid' => $this->uid, 'name' => $this->getThumbName()]);
        } else {
            return url('assets/images/placeholder.jpg');
        }
    }

    /**
     * transform URL.
     */
    public function untransform()
    {
        // @todo workaround
        // to avoid exception thrown by route(template_assets)
        if (is_null($this->uid)) {
            return;
        }

        // replace absolute urls
        $this->content = str_replace(
            route('template_assets', ['uid' => $this->uid]).'/',
            '',
            tinyDocTypeUntransform($this->content)
        );

        // remove title tag
        $this->content = strip_tags_only($this->content, 'title');
    }

    /**
     * transform URL.
     */
    public function transform($useTrackingHost = false)
    {
        $this->content = $this->transformWithoutUpdate($this->content, $useTrackingHost);
        return $this->content;
    }

    /**
     * transform URL.
     */
    public function transformWithoutUpdate($html, $useTrackingHost = false)
    {
        // @todo workaround
        // to avoid exception thrown by route(template_assets)
        if (is_null($this->uid)) {
            return;
        }

        // Notice that there are 2 types of assets that need to untransform
        // #1 Campaign relative URL
        // #2 User assets (uploaded by file manager) absolute URL
        //
        $host = config('app.url'); // Default host
        
        if ($useTrackingHost) {
            // Replace #2
            $html = str_replace($host, $this->getTrackingHost(), $html);
            $host = $this->getTrackingHost();
        }

        // Replace #1
        $path = join_url($host, route('template_assets', ['uid' => $this->uid], false));
        $html = \Acelle\Library\Tool::replaceTemplateUrl($html, $path);

        // By the way, fix <html>
        $html = tinyDocTypeTransform($html);

        return $html;
    }

    /**
     * transform URL.
     */
    public function getParsedContent()
    {
        $body = $this->transformWithoutUpdate($this->content, $useTrackingHost = false);
        return $body;
    }

    /**
     * Build Email HTML content.
     *
     * @return string
     */
    public function getHtmlContent($subscriber = null, $msgId = null, $server = null)
    {
        // @note: IMPORTANT: the order must be as follows
        // * addTrackingURL
        // * appendOpenTrackingUrl
        // * tagMessage
        // STEP 01. Get RAW content
        $body = $this->content;

        // STEP 02. Append footer
        // Not applied
        
        // STEP 03. Parse RSS
        if (Setting::isYes('rss.enabled')) {
            $body = Rss::parse($body);
        }

        // STEP 04. Replace Bare linefeed
        // Replace bare line feed char which is not accepted by Outlook, Yahoo, AOL...
        // Not applied

        // STEP 04.1. Check woocommerce items
        $body = $this->wooTransform($body);

        // STEP 05. Transform URLs
        // Note that $useTrackingHost is not needed
        $body = $this->transformWithoutUpdate($body, $useTrackingHost = false);

        if (is_null($msgId)) {
            $msgId = 'SAMPLE'; // for preview mode
        }

        // STEP 08. Transform Tags
        if (!is_null($subscriber)) {
            // Transform tags
            $body = $this->tagMessage($body, $subscriber, $msgId, $server);
        }

        // STEP 09. Make CSS inline
        //
        // Transform CSS/HTML content to inline CSS
        // Be carefule, it will make
        //       <a href="{BUNSUBSCRIBE_URL}"
        // become
        //       <a href="%7BUNSUBSCRIBE_URL%7D"
        $body = $this->inlineHtml($body);

        return $body;
    }

    public function wooTransform($body)
    {
        // find all links from contents
        $document = HtmlDomParser::str_get_html($body);

        // Woo Items List
        foreach ($document->find('[builder-element=ProductListElement]') as $element) {
            $max = $element->getAttribute('data-max-items');
            $display = $element->getAttribute('data-display');
            $sort = $element->getAttribute('data-sort-by');

            $request = request();
            $request->merge(['per_page' => $max]);
            $request->merge(['sort_by' => $sort]);

            $items = Product::search($request)->paginate($request->per_page)
                ->map(function ($product, $key) {
                    return [
                        'id' => $product->uid,
                        'name' => $product->title,
                        'price' => $product->price,
                        'image' => action('ProductController@image', $product->uid),
                        'description' => substr(strip_tags($product->description), 0, 100),
                        'link' => action('ProductController@index'),
                    ];
                })->toArray();
            $itemsHtml = [];
            foreach ($items as $item) {
                // $element->find('.woo-items')[0]->innertext = 'dddddd';
                $itemsHtml[] = '
                    <div class="woo-col-item mb-4 mt-4 col-md-' . (12/$display) . '">
                        <div class="">
                            <div class="img-col mb-3">
                                <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <a style="width:100%" href="'.$item["link"].'" class="mr-4"><img width="100%" src="'.($item["image"] ? $item["image"] : url('images/cart_item.svg')).'" style="max-height:200px;max-width:100%;" /></a>
                                </div>
                            </div>
                            <div class="">
                                <p class="font-weight-normal product-name mb-1">
                                    <a style="color: #333;" href="'.$item["link"].'" class="mr-4">'.$item["name"].'</a>
                                </p>
                                <p class=" product-description">'.$item["description"].'</p>
                                <p><strong>'.$item["price"].'</strong></p>
                                <a href="'.$item["link"].'" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                    ' . trans('messages.automation.view_more') . '
                                </a>
                            </div>
                        </div>
                    </div>
                ';
            }

            $element->find('.products')[0]->innertext = implode('', $itemsHtml);
        }

        // Woo Single Item
        foreach ($document->find('[builder-element=ProductElement]') as $element) {
            $productId = $element->getAttribute('product-id');

            if ($productId) {
                $product = Product::findByUid($productId);

                $item = [
                    'id' => $product->uid,
                    'name' => $product->title,
                    'price' => $product->price,
                    'image' => action('ProductController@image', $product->uid),
                    'description' => substr(strip_tags($product->description), 0, 100),
                    'link' => action('ProductController@index'),
                ];
                // $element->find('.product-name', 0)->innertext = $item["name"];
                // $element->find('.product-description', 0)->innertext = $item["description"];
                // $element->find('.product-link', 0)->href = $item["link"];
                // $element->find('.product-price', 0)->innertext = $item["price"];
                $element->find('.product-link img', 0)->src = $item["image"];
                $html = $element->innertext;
                $html = str_replace('*|PRODUCT_NAME|*', $item["name"], $html);
                $html = str_replace('*|PRODUCT_DESCRIPTION|*', $item["description"], $html);
                $html = str_replace('*|PRODUCT_URL|*', $item["link"], $html);
                $html = str_replace('*|PRODUCT_PRICE|*', $item["price"], $html);
                // $html = str_replace('*|PRODUCT_QUANTITY|*', $item["quantity"], $html);
                $element->innertext = $html;
            }
        }

        $body = $document;

        return $body;
    }

    /**
     * Upload asset.
     */
    public function uploadAsset($file)
    {
        if ($this->customer) {
            // upload to user home/files/ folder
            return $this->customer->user->uploadAsset($file);
        } else {
            // upload to template
            return $this->admin->user->uploadAsset($file);
        }
    }

    /**
     * Template tags.
     *
     * All availabel template tags
     */
    public static function builderTags($list = null)
    {
        $tags = self::tags($list);

        $result = [];

        if (true) {
            
            // Unsubscribe link
            $result[] = [
                'type' => 'label',
                'text' => '<a href="{UNSUBSCRIBE_URL}">' . trans('messages.editor.unsubscribe_text') . '</a>',
                'tag' => '{UNSUBSCRIBE_LINK}',
                'required' => true,
            ];
            
            // web view link
            $result[] = [
                'type' => 'label',
                'text' => '<a href="{WEB_VIEW_URL}">' . trans('messages.editor.click_view_web_version') . '</a>',
                'tag' => '{WEB_VIEW_LINK}',
                'required' => true,
            ];
        }

        foreach ($tags as $tag) {
            $result[] = [
                'type' => 'label',
                'text' => '{'.$tag['name'].'}',
                'tag' => '{'.$tag['name'].'}',
                'required' => true,
            ];
        }

        return $result;
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function getBuilderTemplates($customer)
    {
        $result = [];

        // Gallery
        $templates = self::shared()
            ->orWhere('customer_id', '=', $customer->id)
            ->orderBy('customer_id')
            ->get();

        foreach ($templates as $template) {
            $result[] = [
                'name' => $template->name,
                'url' => action('TemplateController@builderChangeTemplate', ['uid' => $this->uid, 'change_uid' => $template->uid]),
                'thumbnail' => $template->getThumbUrl(),
            ];
        }

        return $result;
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function getBuilderAdminTemplates()
    {
        $result = [];

        // Gallery
        $templates = self::shared()
            ->get();

        foreach ($templates as $template) {
            $result[] = [
                'name' => $template->name,
                'url' => action('Admin\TemplateController@builderChangeTemplate', ['uid' => $this->uid, 'change_uid' => $template->uid]),
                'thumbnail' => $template->getThumbUrl(),
            ];
        }

        return $result;
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function changeTemplate($template)
    {
        $this->content = $template->content;
        $this->save();

        // delete current template folder
        \Acelle\Library\Tool::xdelete($this->getStoragePath());
        
        // Copy uploaded folder
        if (file_exists($this->getStoragePath())) {
            if (!file_exists($this->getStoragePath())) {
                mkdir($this->getStoragePath(), 0777, true);
            }

            \Acelle\Library\Tool::xcopy($template->getStoragePath(), $this->getStoragePath());
        }
    }

    /**
     * Upload template thumbnail.
     *
     * @return mixed
     */
    public function uploadThumbnail($file)
    {
        $file->move($this->getStoragePath(), 'thumbnail.png');
    }

    /**
     * Upload template thumbnail Url.
     *
     * @return mixed
     */
    public function uploadThumbnailUrl($url)
    {
        $contents = file_get_contents($url);
        file_put_contents($this->getStoragePath().'thumbnail.png', $contents);
    }

    public function inlineHtml($html)
    {
        return $html;
    }

    /**
     * Create template from dir.
     *
     * @return Template
     */
    public static function createFromDir($attributes, $dir)
    {
        $template = new self();
        $template->name = $attributes['name'];
        $template->builder = $attributes['builder'];

        $template->save();

        $template->loadFromDirectory($dir);

        if (isset($attributes['category'])) {
            $attributes['category']->templates()->attach($template->id);
        }
    }

    public function clearStorage()
    {
        \Acelle\Library\Tool::xdelete($this->getStoragePath());
    }

    public function deleteAndCleanup()
    {
        $this->clearStorage();
        $this->delete();
    }
}
