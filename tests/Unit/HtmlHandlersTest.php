<?php

namespace Tests\Unit;

use Tests\TestCase;
use League\Pipeline\PipelineBuilder;
use Acelle\Library\HtmlHandler\ParseRss;
use Acelle\Library\HtmlHandler\ReplaceBareLineFeed;
use Acelle\Library\HtmlHandler\AppendHtml;
use Acelle\Library\HtmlHandler\TransformTag;
use Acelle\Model\Subscriber;
use Acelle\Model\Campaign;
use Acelle\Model\MailList;
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

        $this->assertTrue(strlen($out) > strlen($html) * 5);
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

        $this->assertEquals($out, '<html><body><div>Content main</div><a>click me</a><p>read me</p></body></html>');
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

        $this->assertEquals($out, "<html><body><div>Campaign: 'Campaign' Subscriber: '000000'</div><a>click me</a><p>read me</p></body></html>");
    }
}
