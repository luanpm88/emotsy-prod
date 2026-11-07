<?php

namespace Tests\Unit;

use Acelle\Model\SendingServerSendGrid;
use Tests\TestCase;

/**
 * A sending domain typed with capitals ("SteveJober.com") could never be
 * verified: SendGrid lists it lower-cased, the lookup compared exactly, found
 * nothing, and Verify answered "Server Error" (seen on app.emotsy.com, 2026-11-06).
 */
class SendGridDomainLookupTest extends TestCase
{
    /** Stands in for the SendGrid SDK's fluent client and records what was called. */
    private function fakeSendGrid(array $listed): object
    {
        $client = new class ($listed) {
            public array $calls = [];

            public function __construct(private array $listed)
            {
            }

            public function __call($name, $arguments)
            {
                $this->calls[] = $name;

                return $this;
            }

            public function get()
            {
                return $this->response($this->listed);
            }

            public function post()
            {
                $this->calls[] = 'post';

                return $this->response([]);
            }

            private function response(array $data): object
            {
                return new class (json_encode($data)) {
                    public function __construct(private string $body)
                    {
                    }

                    public function body()
                    {
                        return $this->body;
                    }
                };
            }
        };

        return (object) ['client' => $client];
    }

    private function listedDomain(string $typedName, bool $valid): array
    {
        return [
            'id' => 33791817,
            'domain' => strtolower($typedName),
            'subdomain' => substr(md5($typedName), 0, 12),
            'valid' => $valid,
            'dns' => [
                'mail_server' => ['valid' => $valid],
                'dkim' => ['valid' => $valid],
                'subdomain_spf' => ['valid' => $valid],
            ],
        ];
    }

    public function test_a_domain_typed_with_capitals_is_found_and_revalidated(): void
    {
        $sendgrid = $this->fakeSendGrid([$this->listedDomain('SteveJober.com', true)]);

        $server = new SendingServerSendGrid();
        $server->client = $sendgrid;

        $result = $server->checkDomainVerificationStatus((object) ['name' => 'SteveJober.com']);

        $this->assertSame([true, true, true, true], $result);
        // SendGrid was asked to re-check the DNS, which never happened before.
        $this->assertContains('validate', $sendgrid->client->calls);
        $this->assertContains('post', $sendgrid->client->calls);
    }

    public function test_a_different_domain_is_still_not_matched(): void
    {
        $sendgrid = $this->fakeSendGrid([$this->listedDomain('other.com', true)]);

        $server = new SendingServerSendGrid();
        $server->client = $sendgrid;

        $this->expectExceptionMessage('Cannot verify domain against SendGrid');

        $server->checkDomainVerificationStatus((object) ['name' => 'SteveJober.com']);
    }
}
