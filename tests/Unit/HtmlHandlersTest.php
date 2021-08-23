<?php

namespace Tests\Unit;

use Tests\TestCase;
use League\Pipeline\PipelineBuilder;
use Acelle\Library\HtmlHandler\ParseRss;
use Acelle\Library\HtmlHandler\ReplaceBareLineFeed;
use Acelle\Library\HtmlHandler\AppendHtml;
use Acelle\Library\HtmlHandler\TransformTag;
use Acelle\Library\HtmlHandler\InjectTrackingPixel;
use Acelle\Library\HtmlHandler\MakeInlineCss;
use Acelle\Library\HtmlHandler\TransformUrl;
use Acelle\Library\HtmlHandler\AddDoctype;
use Acelle\Library\HtmlHandler\RemoveTitleTag;
use Acelle\Model\Subscriber;
use Acelle\Model\Campaign;
use Acelle\Model\MailList;
use Acelle\Model\Template;
use Mockery;
use Exception;

class HtmlHandlersTest extends TestCase
{
    public function test_parse_rss()
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ParseRss);
        $html = "{% set rss = rss('http://rss.cnn.com/rss/edition.rss', 5) %}
            {% for post in rss.item %}
                <h3>{{ post.title }}</h3>
                <p>{{ post.description | raw }}</p>
                <hr>
            {% endfor %}";

        $out = $pipeline->build()->process($html);

        $this->assertTrue(strlen($out) > strlen($html));
    }

    public function test_replace_bare_line_feed()
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ReplaceBareLineFeed);
        $html = "Hello\nWorld\nFrom\r\nLaravel\n";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, "Hello\r\nWorld\r\nFrom\r\nLaravel");
    }

    public function test_append_html()
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new AppendHtml('<a>click me</a>'));
        $pipeline->add(new AppendHtml('<p>read me</p>'));

        $html = "<html><body><div>Content main</div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, '<!DOCTYPE html><html><body><div>Content main</div><a>click me</a><p>read me</p></body></html>');
    }

    public function test_transform_tag()
    {
        // List
        $list = Mockery::mock(new MailList());

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->shouldReceive('isStdClassSubscriber')->andReturn(true);

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->uid = '000000';

        // Pipe
        $pipeline = new PipelineBuilder();
        $pipeline->add(new TransformTag($campaign, $subscriber, $msgId = 'TEST'));
        $pipeline->add(new AppendHtml('<a>click me</a>'));
        $pipeline->add(new AppendHtml('<p>read me</p>'));

        $html = "<html><body><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, "<!DOCTYPE html><html><body><div>Campaign: 'Campaign' Subscriber: '000000'</div><a>click me</a><p>read me</p></body></html>");
    }

    public function test_inject_tracking_pixel()
    {
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));

        $pipeline = new PipelineBuilder();
        $pipeline->add(new InjectTrackingPixel('MSGID'));

        $html = "<html><body><div>Content main</div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, '<!DOCTYPE html><html><body><div>Content main</div><img src="http://localhost/p/TVNHSUQ/open" width="0" height="0" alt="" style="visibility:hidden"></body></html>');
    }

    public function test_make_inline_css()
    {
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));

        $pipeline = new PipelineBuilder();
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));

        $html = "<html><body><div class='main'><a>Content main</a><span class='big blue '>Test<span></div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, '<html><body><div class="main"><a style="color:green">Content main</a><span class="big blue " style="font-weight:600;color:blue">Test<span></span></span></div></body></html>');
    }

    public function test_test_campaign_html_content()
    {
        // Template
        $html = "<html><body><a href='/hello/world'></body></html>";
        $template = Mockery::mock(new Template(['name' => 'Template']));
        $template->generateUid();
        $template->content = $html;
        $msgId = 'TEST';
        $domain = null;

        // List
        $list = Mockery::mock(new MailList());

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->shouldReceive('isStdClassSubscriber')->andReturn(true);

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->uid = '000000';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype);
        $pipeline->add(new RemoveTitleTag);
        $pipeline->add(new ReplaceBareLineFeed);
        $pipeline->add(new AppendHtml('<div>Hello world éèéêôâ</div>'));
        $pipeline->add(new ParseRss);
        $pipeline->add(new TransformTag($campaign, $subscriber, 'MSGID', $server = null));
        $pipeline->add(new TransformUrl($template, 'MSGID', $trackingDomain = null));
        $pipeline->add(new MakeInlineCss( [storage_path('tests/sample.css')] ));
        $pipeline->add(new InjectTrackingPixel('MSGID'));

        $html = $pipeline->build()->process("<title class='blue'>\nThisis the template title</title><a class=' big blue' href='images/logo.png'></a><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div>");

        $this->assertTrue(strpos($html, '<!DOCTYPE html>') === 0);
        $this->assertTrue(strpos($html, '<title>') === false);
        $this->assertTrue(strpos($html, "\n") === false);
        $this->assertTrue(strpos($html, '<div>Hello world éèéêôâ</div>') !== false);
        $this->assertTrue(strpos($html, "'000000'") !== false);
    }
}
