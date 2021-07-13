<ul class="nav nav-tabs nav-underline" id="pills-tab" role="tablist">
    @foreach (Acelle\Model\TemplateCategory::all() as $cat)
        <li class="nav-item">
            <a class="nav-link {{ isset($category) && $category->uid == $cat->uid ? 'active' : '' }} choose-template-tab" href="{{ action('CampaignController@templateLayout', [
                'uid' => $campaign->uid,
                'category_uid' => $cat->uid,
            ]) }}">
                {{ $cat->name }}
            </a>
        </li>
    @endforeach
    <li class="nav-item">
        <a class="nav-link {{ actionName() == 'templateUpload' ? 'active' : '' }} choose-template-tab nav-link" href="{{ action('CampaignController@templateUpload', $campaign->uid) }}">Upload</a></li>
</ul>