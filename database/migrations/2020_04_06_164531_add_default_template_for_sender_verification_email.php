<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultTemplateForSenderVerificationEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $html = '<!DOCTYPE html><html><head></head><body><p>&nbsp;</p><div style="background-color: #efefef;"><div style="background: #ffffff; margin: 0px auto; max-width: 600px;"><table style="background: #ffffff; width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 0px; text-align: center;"><div class="m_3161037663227291404mj-column-per-100" style="font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: middle; width: 100%;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="background-color: #ffffff; vertical-align: middle; padding: 0px;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-size: 0px; padding: 15px; word-break: break-word;" align="left"><table style="border-collapse: collapse; border-spacing: 0px;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 127px;">&nbsp;</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div><div style="background: #ffffff; margin: 0px auto; max-width: 600px;"><table style="background: #ffffff; width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 0px; text-align: center;"><div class="m_3161037663227291404mj-column-per-100" style="font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;"><table style="vertical-align: top;" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-size: 0px; padding: 0px; word-break: break-word;" align="center"><table style="border-collapse: collapse; border-spacing: 0px;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 600px;">&nbsp;</td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div><div style="background: #ffffff; margin: 0px auto; max-width: 600px;"><table style="background: #ffffff; width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 30px 0px; text-align: center;"><div class="m_3161037663227291404mj-column-per-100" style="font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="vertical-align: top; padding: 0px 8%;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-size: 0px; padding: 0px; word-break: break-word;" align="left"><div style="font-family: Tahoma,Arial,sans-serif; font-size: 13px; line-height: 22px; text-align: left; color: #787878;"><p style="text-align: center; line-height: 32px; margin: 0px; font-size: 22px; color: #191919; font-family: Tahoma,Arial,sans-serif;">Identity Verification</p><p style="line-height: 20px; text-align: center; margin: 0px; font-size: 15px; color: #606060; font-family: Tahoma,Arial,sans-serif;"><span style="color: #787878; padding-top: 10px; display: block;">Dear {USER_NAME}! Thank you for registering your email <strong>{USER_EMAIL}</strong> with us. Please click the link below to verify your email address against our service. If you do not request this verification, please just ignore this email.</span> <br />{VERIFICATION_LINK}</p></div></td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div><div style="background: #ffffff; margin: 0px auto; max-width: 600px;"><table style="background: #ffffff; width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 0px; text-align: center;"><div class="m_3161037663227291404mj-column-per-100" style="font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: bottom; width: 100%;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="vertical-align: bottom; padding: 0px 8%;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-size: 0px; padding: 20px 0px 30px; word-break: break-word;" align="left"><div style="font-family: Tahoma,Arial,sans-serif; font-size: 13px; line-height: 1; text-align: left; color: #000000;"><p style="text-align: center; font-size: 15px; color: #606060;">Thank you</p></div></td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div><div style="background: #efefef; margin: 0px auto; max-width: 600px;"><table style="background: #efefef; width: 100%;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="direction: ltr; font-size: 0px; padding: 20px 0px 40px; text-align: center;"><div class="m_3161037663227291404mj-column-per-100" style="font-size: 0px; text-align: left; direction: ltr; display: inline-block; vertical-align: top; width: 100%;"><table style="vertical-align: top; height: 79px; width: 100%;" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr style="height: 66px;"><td style="font-size: 0px; padding: 25px 30px 0px; word-break: break-word; height: 66px;" align="left"><div style="font-family: Tahoma,Arial,sans-serif; font-size: 9px; line-height: 11px; text-align: left; color: #868686;"><p style="line-height: 16px; text-align: center; margin: 0px; font-size: 9px; color: #868686; font-family: Tahoma,Arial,sans-serif;"><span style="font-size: 12px;">DISCLAIMER</span> <br />The content of this email is confidential and intended for the recipient specified in message only. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you received this message by mistake, please reply to this message and follow with its deletion, so that we can ensure such a mistake does not occur in the future</p></div></td></tr><tr style="height: 13px;"><td style="font-size: 0px; padding-top: 25px; padding-right: 25px; padding-bottom: 0px; word-break: break-word; height: 13px;" align="left"></td></tr></tbody></table></div></td></tr></tbody></table></div></div></body></html>';


        // Avoid using Olequent Model in migration
        // What if the model gets changed or deleted completely later on?
        DB::insert('INSERT INTO '.table('layouts').' (uid, alias, group_name, type, subject, content) VALUES (?, ?, ?, ?, ?, ?)', [
            uniqid(),
            'sender_verification_email',
            'Verification',
            'email',
            'Verify your sender identity',
            $html,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('layouts', function (Blueprint $table) {
            //
        });
    }
}
