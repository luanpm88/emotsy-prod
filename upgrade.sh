#!/bin/bash

# UPGRADE
# * FROM : "v2.2.0*"
# * TO   : "5aa5359a26320aaf2295e41b355bf5ebe6f08ba3"

if [ -z "$1" ]
then
  echo "Error: please specify the target path" && exit 1
fi

if [ ! -d "$1" ]
then
  echo "Error: directory does not exist" && exit 1
fi

echo "* Replacing files"
cp ./app/Exceptions/CampaignPausedException.php $1/app/Exceptions/CampaignPausedException.php
cp ./app/Helpers/helpers.php $1/app/Helpers/helpers.php
cp ./app/Helpers/ImportSubscribersHelper.php $1/app/Helpers/ImportSubscribersHelper.php
cp ./app/Http/Controllers/Auth/AuthController.php $1/app/Http/Controllers/Auth/AuthController.php
cp ./app/Http/Controllers/AutoEventController.php $1/app/Http/Controllers/AutoEventController.php
cp ./app/Http/Controllers/AutomationController.php $1/app/Http/Controllers/AutomationController.php
cp ./app/Http/Controllers/CampaignController.php $1/app/Http/Controllers/CampaignController.php
cp ./app/Http/Controllers/Controller.php $1/app/Http/Controllers/Controller.php
cp ./app/Http/Controllers/MailListController.php $1/app/Http/Controllers/MailListController.php
cp ./app/Http/Controllers/SegmentController.php $1/app/Http/Controllers/SegmentController.php
cp ./app/Http/Kernel.php $1/app/Http/Kernel.php
cp ./app/Http/routes.php $1/app/Http/routes.php
cp ./app/Jobs/ImportSubscribersJob.php $1/app/Jobs/ImportSubscribersJob.php
cp ./app/Library/QuotaTrackerInterface.php $1/app/Library/QuotaTrackerInterface.php
cp ./app/Library/QuotaTracker.php $1/app/Library/QuotaTracker.php
cp ./app/Library/QuotaTrackerRedis.php $1/app/Library/QuotaTrackerRedis.php
cp ./app/Library/QuotaTrackerStd.php $1/app/Library/QuotaTrackerStd.php
cp ./app/Library/Tool.php $1/app/Library/Tool.php
cp ./app/Model/AutoEvent.php $1/app/Model/AutoEvent.php
cp ./app/Model/AutomatedCampaign.php $1/app/Model/AutomatedCampaign.php
cp ./app/Model/Automation.php $1/app/Model/Automation.php
cp ./app/Model/AutomationsListsSegment.php $1/app/Model/AutomationsListsSegment.php
cp ./app/Model/Campaign.php $1/app/Model/Campaign.php
cp ./app/Model/CampaignsListsSegment.php $1/app/Model/CampaignsListsSegment.php
cp ./app/Model/MailList.php $1/app/Model/MailList.php
cp ./app/Model/Segment.php $1/app/Model/Segment.php
cp ./app/Model/SendingServerAmazonApi.php $1/app/Model/SendingServerAmazonApi.php
cp ./app/Model/SendingServerAmazon.php $1/app/Model/SendingServerAmazon.php
cp ./app/Model/SendingServerAmazonSmtp.php $1/app/Model/SendingServerAmazonSmtp.php
cp ./app/Model/SendingServerMailgunApi.php $1/app/Model/SendingServerMailgunApi.php
cp ./app/Model/SendingServerMailgun.php $1/app/Model/SendingServerMailgun.php
cp ./app/Model/SendingServerMailgunSmtp.php $1/app/Model/SendingServerMailgunSmtp.php
cp ./app/Model/SendingServer.php $1/app/Model/SendingServer.php
cp ./app/Model/SendingServerPhpMail.php $1/app/Model/SendingServerPhpMail.php
cp ./app/Model/SendingServerSendGridApi.php $1/app/Model/SendingServerSendGridApi.php
cp ./app/Model/SendingServerSendmail.php $1/app/Model/SendingServerSendmail.php
cp ./app/Model/SendingServerSmtp.php $1/app/Model/SendingServerSmtp.php
cp ./app/Model/Setting.php $1/app/Model/Setting.php
cp ./app/Model/UserGroup.php $1/app/Model/UserGroup.php
cp ./app/Model/User.php $1/app/Model/User.php
cp ./app/Policies/AutomationPolicy.php $1/app/Policies/AutomationPolicy.php
cp ./app/Policies/MailListPolicy.php $1/app/Policies/MailListPolicy.php
cp ./app/Policies/UserPolicy.php $1/app/Policies/UserPolicy.php
cp ./app/Providers/AppServiceProvider.php $1/app/Providers/AppServiceProvider.php
cp ./build.sh $1/build.sh
cp ./CHANGELOG.md $1/CHANGELOG.md
cp ./composer.json $1/composer.json
cp ./composer.lock $1/composer.lock
cp ./config/app.php $1/config/app.php
cp ./database/migrations/2016_12_28_095315_add_auto_trigger_id_to_tracking_logs.php $1/database/migrations/2016_12_28_095315_add_auto_trigger_id_to_tracking_logs.php
cp ./database/migrations/2017_01_11_114659_create_campaigns_lists_segments_table.php $1/database/migrations/2017_01_11_114659_create_campaigns_lists_segments_table.php
cp ./database/migrations/2017_01_11_174230_add_default_mail_list_id_to_campaigns.php $1/database/migrations/2017_01_11_174230_add_default_mail_list_id_to_campaigns.php
cp ./database/migrations/2017_01_12_155942_delete_list_segmnet_cols_form_campaigns.php $1/database/migrations/2017_01_12_155942_delete_list_segmnet_cols_form_campaigns.php
cp ./database/migrations/2017_01_12_160256_create_automations_lists_segments_table.php $1/database/migrations/2017_01_12_160256_create_automations_lists_segments_table.php
cp ./database/migrations/2017_01_12_161001_add_default_mail_list_id_to_automations.php $1/database/migrations/2017_01_12_161001_add_default_mail_list_id_to_automations.php
cp ./database/migrations/2017_01_12_170519_delete_list_segment_cols_from_automations.php $1/database/migrations/2017_01_12_170519_delete_list_segment_cols_from_automations.php
cp ./database/migrations/2017_01_22_175544_add_sending_servers_quota_column.php $1/database/migrations/2017_01_22_175544_add_sending_servers_quota_column.php
cp ./database/migrations/2017_01_24_092138_update_user_quota.php $1/database/migrations/2017_01_24_092138_update_user_quota.php
cp ./database/seeds/DatabaseSeeder.php $1/database/seeds/DatabaseSeeder.php
cp ./generate-upgrade-script.sh $1/generate-upgrade-script.sh
cp ./phpunit.xml $1/phpunit.xml
cp ./public/css/app.css $1/public/css/app.css
cp ./public/css/theme.css $1/public/css/theme.css
cp ./public/filemanager/config/config.php $1/public/filemanager/config/config.php
cp ./public/index.php $1/public/index.php
cp ./public/js/app.js $1/public/js/app.js
cp ./public/js/editor.js $1/public/js/editor.js
cp ./public/js/jquery.cookie.js $1/public/js/jquery.cookie.js
cp ./resources/lang/default/messages.php $1/resources/lang/default/messages.php
cp ./resources/lang/en/messages.php $1/resources/lang/en/messages.php
cp ./resources/views/account/quota_log.blade.php $1/resources/views/account/quota_log.blade.php
cp ./resources/views/admin/bounce_handlers/_form.blade.php $1/resources/views/admin/bounce_handlers/_form.blade.php
cp ./resources/views/admin/bounce_handlers/_list.blade.php $1/resources/views/admin/bounce_handlers/_list.blade.php
cp ./resources/views/admin/dashboard.blade.php $1/resources/views/admin/dashboard.blade.php
cp ./resources/views/admin/feedback_logs/index.blade.php $1/resources/views/admin/feedback_logs/index.blade.php
cp ./resources/views/admin/feedback_loop_handlers/_list.blade.php $1/resources/views/admin/feedback_loop_handlers/_list.blade.php
cp ./resources/views/admin/languages/_list.blade.php $1/resources/views/admin/languages/_list.blade.php
cp ./resources/views/admin/open_logs/index.blade.php $1/resources/views/admin/open_logs/index.blade.php
cp ./resources/views/admin/sending_domains/_form.blade.php $1/resources/views/admin/sending_domains/_form.blade.php
cp ./resources/views/admin/sending_domains/_list.blade.php $1/resources/views/admin/sending_domains/_list.blade.php
cp ./resources/views/admin/sending_servers/_form.blade.php $1/resources/views/admin/sending_servers/_form.blade.php
cp ./resources/views/admin/sending_servers/_list.blade.php $1/resources/views/admin/sending_servers/_list.blade.php
cp ./resources/views/admin/templates/_list.blade.php $1/resources/views/admin/templates/_list.blade.php
cp ./resources/views/admin/tracking_logs/index.blade.php $1/resources/views/admin/tracking_logs/index.blade.php
cp ./resources/views/admin/tracking_logs/_list.blade.php $1/resources/views/admin/tracking_logs/_list.blade.php
cp ./resources/views/admin/user_groups/_form.blade.php $1/resources/views/admin/user_groups/_form.blade.php
cp ./resources/views/admin/user_groups/_list.blade.php $1/resources/views/admin/user_groups/_list.blade.php
cp ./resources/views/admin/users/_list.blade.php $1/resources/views/admin/users/_list.blade.php
cp ./resources/views/auto_events/show.blade.php $1/resources/views/auto_events/show.blade.php
cp ./resources/views/automations/confirm.blade.php $1/resources/views/automations/confirm.blade.php
cp ./resources/views/automations/_criteria_form.blade.php $1/resources/views/automations/_criteria_form.blade.php
cp ./resources/views/automations/_form.blade.php $1/resources/views/automations/_form.blade.php
cp ./resources/views/automations/_head.blade.php $1/resources/views/automations/_head.blade.php
cp ./resources/views/automations/_list.blade.php $1/resources/views/automations/_list.blade.php
cp ./resources/views/automations/_list_segment_form.blade.php $1/resources/views/automations/_list_segment_form.blade.php
cp ./resources/views/automations/overview/_campaigns_list.blade.php $1/resources/views/automations/overview/_campaigns_list.blade.php
cp ./resources/views/automations/trigger.blade.php $1/resources/views/automations/trigger.blade.php
cp ./resources/views/automations/triggers/_subscriber-event.blade.php $1/resources/views/automations/triggers/_subscriber-event.blade.php
cp ./resources/views/campaigns/confirm.blade.php $1/resources/views/campaigns/confirm.blade.php
cp ./resources/views/campaigns/_info.blade.php $1/resources/views/campaigns/_info.blade.php
cp ./resources/views/campaigns/_list.blade.php $1/resources/views/campaigns/_list.blade.php
cp ./resources/views/campaigns/_list_segment_form.blade.php $1/resources/views/campaigns/_list_segment_form.blade.php
cp ./resources/views/campaigns/_open_click_rate.blade.php $1/resources/views/campaigns/_open_click_rate.blade.php
cp ./resources/views/campaigns/_quick_view.blade.php $1/resources/views/campaigns/_quick_view.blade.php
cp ./resources/views/campaigns/recipients.blade.php $1/resources/views/campaigns/recipients.blade.php
cp ./resources/views/campaigns/schedule.blade.php $1/resources/views/campaigns/schedule.blade.php
cp ./resources/views/campaigns/_subscribers_list.blade.php $1/resources/views/campaigns/_subscribers_list.blade.php
cp ./resources/views/campaigns/template.blade.php $1/resources/views/campaigns/template.blade.php
cp ./resources/views/campaigns/_template_list.blade.php $1/resources/views/campaigns/_template_list.blade.php
cp ./resources/views/campaigns/template_preview.blade.php $1/resources/views/campaigns/template_preview.blade.php
cp ./resources/views/common/flash.blade.php $1/resources/views/common/flash.blade.php
cp ./resources/views/dashboard.blade.php $1/resources/views/dashboard.blade.php
cp ./resources/views/helpers/_date.blade.php $1/resources/views/helpers/_date.blade.php
cp ./resources/views/helpers/form_control.blade.php $1/resources/views/helpers/form_control.blade.php
cp ./resources/views/helpers/_radio.blade.php $1/resources/views/helpers/_radio.blade.php
cp ./resources/views/layouts/account.blade.php $1/resources/views/layouts/account.blade.php
cp ./resources/views/layouts/backend.blade.php $1/resources/views/layouts/backend.blade.php
cp ./resources/views/layouts/_css.blade.php $1/resources/views/layouts/_css.blade.php
cp ./resources/views/layouts/frontend.blade.php $1/resources/views/layouts/frontend.blade.php
cp ./resources/views/layouts/_js.blade.php $1/resources/views/layouts/_js.blade.php
cp ./resources/views/layouts/page.blade.php $1/resources/views/layouts/page.blade.php
cp ./resources/views/layouts/_script_vars.blade.php $1/resources/views/layouts/_script_vars.blade.php
cp ./resources/views/layouts/_user_script.blade.php $1/resources/views/layouts/_user_script.blade.php
cp ./resources/views/lists/_list.blade.php $1/resources/views/lists/_list.blade.php
cp ./resources/views/segments/_select_box.blade.php $1/resources/views/segments/_select_box.blade.php
cp ./resources/views/subscribers/export.blade.php $1/resources/views/subscribers/export.blade.php
cp ./resources/views/subscribers/_export_list.blade.php $1/resources/views/subscribers/_export_list.blade.php
cp ./resources/views/subscribers/import.blade.php $1/resources/views/subscribers/import.blade.php
cp ./resources/views/subscribers/_import_list.blade.php $1/resources/views/subscribers/_import_list.blade.php
cp ./resources/views/templates/_list.blade.php $1/resources/views/templates/_list.blade.php
cp ./resources/views/templates/_list_choose.blade.php $1/resources/views/templates/_list_choose.blade.php
cp ./storage/install/database_init.sql $1/storage/install/database_init.sql
cp ./tests/QuotaTrackerRedisTest.php $1/tests/QuotaTrackerRedisTest.php
cp ./tests/QuotaTrackerStdTest.php $1/tests/QuotaTrackerStdTest.php
cp ./tests/QuotaTrackerTest.php $1/tests/QuotaTrackerTest.php
cp ./tests/SendingServerQuotaTest.php $1/tests/SendingServerQuotaTest.php
cp ./tests/UserQuotaTest.php $1/tests/UserQuotaTest.php
cp ./upgrade.sh $1/upgrade.sh

cd "$1"

echo "* Upgrading packages"
# rm -fr ./vendor
php composer.phar install

echo "* Migrating database"
php artisan config:cache
php artisan migrate

echo "Finished!"
