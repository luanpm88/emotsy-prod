<ul class="nav nav-tabs nav-underline" id="pills-tab" role="tablist">
    @foreach (Acelle\Model\TemplateCategory::getAll() as $cat)
        <li class="nav-item">
            <a class="nav-link {{ request()->category_uid == $cat->uid ? 'active' : '' }} choose-template-tab"
                href="{{ action('CampaignController@templateLayout', [
                    'uid' => $campaign->uid,
                    'category_uid' => $cat->uid,
                    'version_b' => request()->version_b,
            ]) }}">
                {{ $cat->name }}
            </a>
        </li>
    @endforeach
    <li class="nav-item">
        <a class="nav-link {{ (!isset($templateUpload) && !\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('newsletter_templates')) || request()->from == 'mine' ? 'active' : '' }} choose-template-tab nav-link"
            href="{{ action('CampaignController@templateLayout', [
                'uid' => $campaign->uid,
                'from' => 'mine',
                'version_b' => request()->version_b,
            ]) }}">{{ trans('messages.my_templates') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ isset($templateUpload) ? 'active' : '' }} choose-template-tab nav-link"
        href="{{ action('CampaignController@templateUpload', [
            'uid' => $campaign->uid,
            'version_b' => request()->version_b,
        ]) }}">
            {{ trans('messages.upload') }}
        </a>
    </li>
</ul>