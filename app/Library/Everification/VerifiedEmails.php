<?php

namespace Acelle\Library\Everification;

use Exception;
use Acelle\Library\Contracts\VerifyInterface;
use Acelle\Library\Contracts\BulkVerifyInterface;
use Illuminate\Database\Eloquent\Builder;
use Acelle\Library\VerifiedEmailsClient as Client;
use Closure;

class VerifiedEmails implements VerifyInterface, BulkVerifyInterface
{
    protected $apiToken;

    protected $map = [
        'Yes' => 'deliverable',
        'No' => 'undeliverable',
        'Risky' => 'risky',
        'Unknown' => 'unknown',
    ];

    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function getClient()
    {
        return new Client($this->apiToken);
    }

    public function getCredits(): ?int
    {
        $response = $this->getClient()->getCredits();

        return $response;
    }

    public function verify($email)
    {
        $response = $this->getClient()->verify($email);

        return [$this->map[$response['safetosend']], (string) json_encode($response)];
    }

    public function bulkSubmit(Builder $subscriberQuery): string
    {
        $response = $this->getClient()->batchVerify($subscriberQuery->pluck('email')->toArray());

        $batchId = $response['batch_id'];

        return $batchId;
    }

    public function bulkCheck(string $batchId, Closure $doneCallback, Closure $waitCallback): bool
    {

        $response = $this->getClient()->getBatchStatus($batchId);

        if (!$response['status']) {
            if ($waitCallback) {
                $waitCallback(json_encode($response));
            }

            return false;
        } else {
            // fetch results
            $response = $this->getClient()->getBatchResult($batchId);

            foreach($response as $emailRaw) {
                $email = $emailRaw["email"];
                $status = $this->map[$emailRaw['status']];
                $doneCallback($email, $status, json_encode($emailRaw));
            }

            return true;
        }

        return false;
    }

    public function isBulkVerifySupported(): bool
    {
        return true;
    }


    public function getServiceName(): string
    {
        return 'VerifiedEmails.io';
    }

    public function getServiceUrl(): string
    {
        return 'https://verifiedemails.io';
    }
}
