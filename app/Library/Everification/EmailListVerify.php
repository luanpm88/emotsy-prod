<?php

namespace Acelle\Library\Everification;

use Exception;
use Acelle\Library\Contracts\VerifyInterface;
use Acelle\Library\Contracts\BulkVerifyInterface;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Client as GuzzleClient;
use Closure;

class EmailListVerify implements VerifyInterface, BulkVerifyInterface
{
    protected $apiKey;
    protected $map = [
        'ok' => 'deliverable',
        'ok_for_all' => 'deliverable',
        'email_disabled' => 'undeliverable',
        'dead_server' => 'undeliverable',
        'invalid_mx' => 'undeliverable',
        'invalid_syntax' => 'undeliverable',
        'disposable' => 'risky',
        'spamtrap' => 'risky',
        'smtp_protocol' => 'unknown',
        'antispam_system' => 'unknown',
        'unknown' => 'unknown',
    ];

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getCredits(): ?int
    {
        return null;
    }

    public function verify($email)
    {
        $email = "test@example.com";
        $key = $this->apiKey;
        $url = "https://apps.emaillistverify.com/api/verifyEmail?secret=".$key."&email=".$email;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        $response = curl_exec($ch);
        curl_close($ch);

        return [$this->map[$response], (string) json_encode($response)];
    }

    public function bulkSubmit(Builder $subscriberQuery): string
    {
        $key = $this->apiKey;
        $filename = "bulkVerify_" . uniqid() . ".txt";
        $emails = $subscriberQuery->pluck('email')->toArray();
        $filePath = '/Users/luan/Downloads/' . $filename;
        

        // Open the file in append mode
        $file = fopen($filePath, 'a');

        // Write each email to a new line
        foreach ($emails as $email) {
            fwrite($file, $email . "\n");
        }

        // Close the file
        fclose($file);
        $settings['file_contents'] = "$filePath";
        $url = 'https://apps.emaillistverify.com/api/verifyApiFile?secret='.$key.'&filename=my_emails.txt';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $settings);
        $file_id = curl_exec($ch); //you need to save this FILE ID for get file status and download reports in future
        curl_close($ch);

        return $file_id;
    }

    public function bulkCheck(string $batchId, Closure $doneCallback, Closure $waitCallback): bool
    {
        // 1438667
        $response = $this->makeRequest('GET', 'get-result-bulk-verification-task/', [
            'task_id' => $batchId,
        ]); 

        $raw = (string)$response->getBody();
        $response = json_decode($raw, true);

        if ($response['status'] != 'completed') {
            if ($waitCallback) {
                $waitCallback(json_encode($response));
            }

            return false;
        } else {
            foreach ($response['results'] as $email => $result) {
                $email = $email;
                $status = $this->map[$result['status']];
                $doneCallback($email, $status, json_encode($result));
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
        return 'EmailListVerify';
    }

    public function getServiceUrl(): string
    {
        return 'https://apps.emaillistverify.com';
    }
}
