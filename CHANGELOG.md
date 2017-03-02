2.2.0-p13 / 2017-03-03
===================

 * Added: support follow-up messages that have not been opened/clicked
 * Fixed: quota tracking issue for click-to-open rate

2.2.0-p12 / 2017-02-27
===================

 * Added: speed up page loading using cache
 * Added: support additional user group API
 * Fixed: file manager's thumbnail image 
 * Fixed: intermittent glitch with code editor
 * Fixed: template upload issues with old HTML/CSS styles
 * Fixed: translating missing in system email

2.2.0-p11 / 2017-02-22
===================

 * Added: fully multi-process supported
 * Added: support more API for list/subscriber management
 * Changed: new default user permissions
 * Fixed: better handle invalid bounced message
 * Fixed: invalid bounces not showing up correctly
 * Fixed: automation cannot start for certain scenarios

2.2.0-p10 / 2017-02-15
====================

 * Fixed: suppress verbose error message
 * Fixed: do not fork new processes if it is not really needed
 * Fixed: file manager upload limit
 * Fixed: issue with plain text campaign when working with SendGrid
 * Fixed: prevent campaign from being queued more than one time 
 * Fixed: template selection issue on old Firefox browsers
 * Fixed: click-to-open rate not showing up correctly

2.2.0-p9 / 2017-02-10
=====================

 * Fixed: installation issues with exec on restricted OS
 * Added: support Re-captcha
 * Added: include more details in installation error messages
 * Changed: API - return newly created subscriber's UID in the response

2.2.0-p8 / 2017-02-06
=====================
 
 * Added: support advanced background job installation wizard
 * Added: support erasing existing database before initialization
 * Added: support customizing system's default language
 * Fixed: issue with auto login by token

2.2.0-p7 / 2017-01-30
=====================

 * Added: support updating the application configuration without re-installing
 * Added: support sending campaign without using cronjob
 * Fixed: timezone issue while scheduling future campaign

2.2.0-p6* / 2017-01-27
===================

 * Added: support sending campaign to multiple lists / segments
 * Fixed: issue of migrating from v2.0.4
 * Fixed: links sometimes do not work in test email
 * Fixed: intermittent memory issue of PHP 5.6 or below

2.2.0-p5 / 2017-01-23
===================

 * Fixed: sending server's quota 

2.2.0-p4 / 2017-01-20
===================

 * Fixed: file manager URL issue with old browsers
 * Changed: new quota renewal method

2.2.0-p3 / 2017-01-17
===================

 * Fixed: memory limit issue with importing
 * Fixed: MAC OS line-ending issue
 * Fixed: follow-up email is triggered more than one time

2.2.0-p2 / 2017-01-11
===================

 * Fixed: compatibility issues with old PHP versions
 * Fixed: compatibility issues with 
 * Fixed: calendar glitches on some browsers

2.2.0-p1 / 2017-01-08
===================

 * Fixed: mail list export compatibility issue on certain systems
 * Fixed: cannot delete out-dated campaigns
 * Fixed: php-curl compatibility issue
 * Fixed: improve subscribers import performance
 * Fixed: PHP 7.1 compatibility issues
 * Changed: php-xml is now required
 * Changed: refractor of the system jobs
 * Changed: only one cronjob is required
 * Added: support automation/autorespond functionality

2.0.4-p27 & 2.0.4-p28 / 2016-11-09
===================

 * Added: send a test email of campaign
 * Added: better internationalization support: allow creating new language
 * Added: better internationalization support: support custom translation
 * Changed: support running several campaigns at the same time

2.0.4-p26 / 2016-11-08

 * Added: send a test email of campaign
 * Added: better internationalization support: allow creating new language
 * Added: better internationalization support: support custom translation
 * Changed: support running several campaigns at the same time

2.0.4-p25 / 2016-11-01
==================

 * Fixed: certain encoding may cause corrupt links
 * Changed: default user policy change

2.0.4-p24 / 2016-10-28
==================
 
 * Fixed: subscriber import does not work well with async
 * Fixed: runtime-message-id with extra invisible space
 * Fixed: directory permission checking error
 * Fixed: campaign's wrong subscribers count in certain cases
 * Fixed: config cache with invalid values

2.0.4-p23 / 2016-10-23
==================

 * Added: ElasticEmail API/SMTP support
 * Fixed: reduce the delay time when sending email through SMTP
 * Changed: delivery server encryption method is no longer required

2.0.4-p22 / 2016-10-19
==================
 
 * Added: create-user API
 * Added: quick login support
 * Added: copy campaign
 * Fixed: detect more environment dependencies when installing
 * Fixed: layout crashes for old IE browser
 * Fixed: application crashes when mbstring is missing
 * Fixed: chart view issues on MS Edge

2.0.4-p20 / 2016-10-12
==================

 * Fixed: installation wizard compatibility issue
 * Added: drag & drop email builder

2.0.4-p19 / 2016-10-03
==================

 * Fixed: certain types of links are not tracked

2.0.4-p18 / 2016-10-02
==================

 * Fixed: open tracking causes broken image in email content

2.0.4-p17 / 2016-10-02
==================

 * Fixed intermittent issues with bar chart in Safari
 * Changed click-to-open ratio is now based on open count

2.0.4-p16 / 2016-09-30
==================

 * Fixed listing sometimes crashes due to slow internet connection
 * Fixed do not allow users to enter invalid IMAP encryption method
 * Fixed list import intermittent issue for ISO encoded CSV
 * Added pie chart visualization for top countries by open
 * Added pie chart visualization for top countries by click
 * Updated text & hints on the UI
 * Changed dashboard UI now contains more information
 * Changed click-rate is no longer computed based on specific URL

2.0.4-p11 / 2016-09-27
==================

 * Fixed SSL issue for bounce handler
 * Fixed bounce handler does not work correctly for certain type of IMAP servers
 * Changed sending campaign can be deleted
 * Added full support for SendGrid (web API & SMTP)

2.0.4-p8 / 2016-09-20
==================

 * Fixed HTML editor sometimes crashes on MS Edge 
 * Added clean up invalid bytes sequence in email content
 * Added check php-gd library availability in the installation wizard

2.0.4 / 2016-09-13
==================

This is the first publicly released version of Acelle Mail webapp (which was previously Turbo Mail 1.x, a private project at National Information System institute)

 * Fixed better compatibility with MS Edge browser
 * Multi-process support for sending large amounts of email
 * Added Mailgun API/SMTP integration full support
 * Added embeded form customization support
 * Added email extra headers for better RFC compliance
 * Added template gallery & template customization support

2.0.3 / 2016-07-01
==================

 * Added DKIM singing support for out-going message
 * Added better integration with Amazon SES
 * Added template preview support
 * Added bounce logging with more information
 * Changed refractor of quota system
