#!/bin/bash

# UPGRADE
# * FROM : "v2.2.0*"
# * TO   : "v2.2.0-p5"

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
cp ./app/Http/Controllers/Auth/AuthController.php $1/app/Http/Controllers/Auth/AuthController.php
cp ./app/Http/Controllers/CampaignController.php $1/app/Http/Controllers/CampaignController.php
cp ./app/Jobs/ImportSubscribersJob.php $1/app/Jobs/ImportSubscribersJob.php
cp ./app/Library/QuotaTrackerInterface.php $1/app/Library/QuotaTrackerInterface.php
cp ./app/Library/QuotaTracker.php $1/app/Library/QuotaTracker.php
cp ./app/Library/QuotaTrackerRedis.php $1/app/Library/QuotaTrackerRedis.php
cp ./app/Library/QuotaTrackerStd.php $1/app/Library/QuotaTrackerStd.php
cp ./app/Library/Tool.php $1/app/Library/Tool.php
cp ./app/Model/AutoEvent.php $1/app/Model/AutoEvent.php
cp ./app/Model/Campaign.php $1/app/Model/Campaign.php
cp ./app/Model/MailList.php $1/app/Model/MailList.php
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
cp ./app/Model/UserGroup.php $1/app/Model/UserGroup.php
cp ./app/Model/User.php $1/app/Model/User.php
cp ./app/Policies/MailListPolicy.php $1/app/Policies/MailListPolicy.php
cp ./app/Policies/UserPolicy.php $1/app/Policies/UserPolicy.php
cp ./build.sh $1/build.sh
cp ./CHANGELOG.md $1/CHANGELOG.md
cp ./composer.json $1/composer.json
cp ./composer.lock $1/composer.lock
cp ./config/app.php $1/config/app.php
cp ./database/migrations/2017_01_22_175544_add_sending_servers_quota_column.php $1/database/migrations/2017_01_22_175544_add_sending_servers_quota_column.php
cp ./database/migrations/2017_01_24_092138_update_user_quota.php $1/database/migrations/2017_01_24_092138_update_user_quota.php
cp ./generate-upgrade-script.sh $1/generate-upgrade-script.sh
cp ./phpunit.xml $1/phpunit.xml
cp ./public/css/app.css $1/public/css/app.css
cp ./public/filemanager/config/config.php $1/public/filemanager/config/config.php
cp ./public/js/app.js $1/public/js/app.js
cp ./public/js/editor.js $1/public/js/editor.js
cp ./resources/lang/default/messages.php $1/resources/lang/default/messages.php
cp ./resources/lang/en/messages.php $1/resources/lang/en/messages.php
cp ./resources/views/account/quota_log.blade.php $1/resources/views/account/quota_log.blade.php
cp ./resources/views/admin/bounce_handlers/_form.blade.php $1/resources/views/admin/bounce_handlers/_form.blade.php
cp ./resources/views/admin/bounce_handlers/_list.blade.php $1/resources/views/admin/bounce_handlers/_list.blade.php
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
cp ./resources/views/automations/_head.blade.php $1/resources/views/automations/_head.blade.php
cp ./resources/views/automations/_list.blade.php $1/resources/views/automations/_list.blade.php
cp ./resources/views/automations/overview/_campaigns_list.blade.php $1/resources/views/automations/overview/_campaigns_list.blade.php
cp ./resources/views/automations/trigger.blade.php $1/resources/views/automations/trigger.blade.php
cp ./resources/views/campaigns/_list.blade.php $1/resources/views/campaigns/_list.blade.php
cp ./resources/views/campaigns/schedule.blade.php $1/resources/views/campaigns/schedule.blade.php
cp ./resources/views/campaigns/_subscribers_list.blade.php $1/resources/views/campaigns/_subscribers_list.blade.php
cp ./resources/views/campaigns/template.blade.php $1/resources/views/campaigns/template.blade.php
cp ./resources/views/campaigns/template_preview.blade.php $1/resources/views/campaigns/template_preview.blade.php
cp ./resources/views/common/flash.blade.php $1/resources/views/common/flash.blade.php
cp ./resources/views/dashboard.blade.php $1/resources/views/dashboard.blade.php
cp ./resources/views/layouts/_css.blade.php $1/resources/views/layouts/_css.blade.php
cp ./resources/views/layouts/frontend.blade.php $1/resources/views/layouts/frontend.blade.php
cp ./resources/views/layouts/_js.blade.php $1/resources/views/layouts/_js.blade.php
cp ./resources/views/lists/_list.blade.php $1/resources/views/lists/_list.blade.php
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
