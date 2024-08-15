<?php

namespace Acelle\Library\Everification;

use Exception;
use Closure;
use Acelle\Library\Contracts\VerifyInterface;
use Acelle\Library\Contracts\BulkVerifyInterface;
use Illuminate\Database\Eloquent\Builder;
use Acelle\Library\InMemoryRateTracker;
use Acelle\Library\RateLimit;

use function Acelle\Helpers\curl_download;
use function Acelle\Helpers\read_csv;
use function Acelle\Helpers\execute_with_limits;

class MyEmailVerifier implements VerifyInterface, BulkVerifyInterface
{
    protected $apiKey;
    protected $resultMap = [
        'Valid' => 'deliverable',
        'Unknown' => 'unknown',
        'Invalid' => 'undeliverable',
        'Catch-all' => 'unknown',
        'Grey-listed' => 'unknown',
    ];

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function verify($email)
    {
        throw new Exception('Single email address verification not supported');
    }

    public function getCredits(): ?int
    {
        // check if progress is done
        $url = "https://client.myemailverifier.com/verifier/getcredits/{$this->apiKey}";

        $ch = curl_init($url);

        // Set options for data retrieval
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Capture the response
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects (optional)
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute the curl request

        $rawResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception("Something wrong while getting available credtis from MyEmailVerifier.com: ".curl_error($ch));
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (200 != $httpcode) {
            throw new Exception("Something wrong while getting available credtis from MyEmailVerifier.com: ".$rawResponse);
        }

        $response = json_decode($rawResponse, true);
        if (empty($response) || !array_key_exists('credits', $response)) {
            throw new Exception("Something wrong while getting available credtis from MyEmailVerifier.com 'credits' field is required: {$rawResponse}");
        }

        return (int)$response['credits'];
    }

    public function bulkSubmit(Builder $subscriberQuery): string
    {
        // Likely to throw RateLimitExceeded
        $fileId = execute_with_limits([$this->initRateLimitTracker()], $creditTrackers = [], function () use ($subscriberQuery) {
            return $this->submit($subscriberQuery);
        });

        return $fileId;
    }

    private function submit(Builder $subscriberQuery): string
    {
        $basePath = storage_path('app/verification/Bouncify/upload/');
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, $recursive = true);
        }

        $filepath = join_paths($basePath, md5($this->apiKey)."-".uniqid().".txt");

        // Write
        file_put_contents($filepath, implode(PHP_EOL, $subscriberQuery->pluck('email')->toArray()));

        try {
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://api.bouncify.io/v1/bulk?apikey={$this->apiKey}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('local_file' => new CURLFILE($filepath)),
            ));

            $rawResponse = curl_exec($ch); //return FILE ID to get file status and download url

            if (curl_errno($ch)) {
                throw new Exception('Something wrong connecting to Bouncify.io: '.curl_error($ch));
            }

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (200 != $httpcode) {
                throw new Exception('Something wrong from Bouncify.io: '.$rawResponse);
            }

            $response = json_decode($rawResponse, true);

            if (empty($response) || !array_key_exists('job_id', $response) || empty($response['job_id'])) {
                throw new Exception('Invalid response from Bouncify.io ("job_id" is required): '.$rawResponse);
            }

            // JOB
            $jobId = $response['job_id'];

            // Start
            $ch = curl_init();
            curl_setopt_array($ch, array(
                // Replace jobId with your list's jobId and replace API_KEY with your API Key, you need to start verification
                CURLOPT_URL => "https://api.bouncify.io/v1/bulk/{$jobId}?apikey={$this->apiKey}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
            ));

            $rawResponse = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception('Something wrong running job at Bouncify.io: '.curl_error($ch));
            }

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (200 != $httpcode) {
                throw new Exception('Something wrong from Bouncify.io: '.$rawResponse);
            }

            $response = json_decode($rawResponse, true);

            if (empty($response) || !array_key_exists('success', $response) || $response['success'] == false) {
                throw new Exception('Failed to start verifying '.$rawResponse);
            }

            curl_close($ch);
        } finally {
            \Illuminate\Support\Facades\File::delete($filepath);
        }
    }

    public function initRateLimitTracker()
    {
        $limits = [
            new RateLimit(15, 1, 'minute', '15 per minute limit'),
        ];

        // Uniq per API KEY
        $key = 'rate-tracker-log-myemailverifier-account-'.md5($this->apiKey);
        $tracker = new InMemoryRateTracker($key, $limits);
        $tracker->cleanup('24 hours');

        return $tracker;
    }

    public function bulkCheck(string $jobId, Closure $doneCallback, Closure $waitCallback = null): bool
    {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            // Replace jobId with your list's jobId and API_KEY with your API Key
            CURLOPT_URL => 'https://api.bouncify.io/v1/bulk/{$jobId}?apikey={$this->apiKey}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $rawResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Something wrong while checking job progress (job ID#{$jobId}) from Bouncify: ".curl_error($ch));
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (200 != $httpcode) {
            throw new Exception("Something wrong while checking job progress (job ID#{$jobId}) from Bouncify: ".$rawResponse);
        }

        $response = json_decode($rawResponse, true);
        if (empty($response) || !array_key_exists('status', $response)) {
            throw new Exception("Something wrong while checking job progress (job ID#{$jobId}) from Bouncify. Invalid response ('status' is required): {$rawResponse}");
        }

        if ($response['status'] == "completed") {
            $this->downloadResult($jobId);
            return true;
        } else {
            if ($waitCallback) {
                $waitCallback($rawResponse);
            }
            return false;
        }
    }

    private function downloadResult($jobId, Closure $callback)
    {
        // File name looks like: c98645cbae77b49513efde4aa04fb942-669394a1d9b4e
        $basePath = storage_path('app/verification/Bouncify/');
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, $recursive = true);
        }

        $tmpResultFile = join_paths($basePath, md5($this->apiKey)."-".uniqid());

        try {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                // Replace API_KEY with your API Key and jobId with your list's jobId, you need to download
                CURLOPT_URL => "https://api.bouncify.io/v1/download?jobId={$jobId}?apikey={$this->apiKey}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ));

            $rawResponse = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception("Something wrong while fetching result (job ID#{$jobId}) from Bouncify: ".curl_error($ch));
            }

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (200 != $httpcode) {
                throw new Exception("Something wrong while fetching result (job ID#{$jobId}) from Bouncify: ".$rawResponse);
            }

            $response = json_decode($rawResponse, true);
            if (empty($response) || !array_key_exists('status', $response)) {
                throw new Exception("Something wrong while fetching result (job ID#{$jobId}) from Bouncify. Invalid response ('status' is required): {$rawResponse}");
            }
        } catch(Exception $ex) {
            throw new Exception('Cannot download result from MyEmailVerifier: '.$ex->getMessage());
        }

        list($headers, $count, $results) = read_csv($tmpResultFile, $hasHeaders = true, $ignoreEmptyHeader = true);

        /* Sample result

            [
                "" => "nghi@b-teka.com",
                "Result" => "Valid",
                "RoleBased" => "false",
                "FreeDomain" => "false",
                "Diagnosis" => "Valid mailbox",
            ]
        */

        foreach ($results as $raw) {
            if (!array_key_exists($raw['Result'], $this->resultMap)) {
                throw new Exception('Unknown status code returned from MyEmailVerifier.com: '.$raw['Result']);
            }

            $email = $raw[''];
            $verificationResult = $this->resultMap[$raw['Result']];
            $callback($email, $verificationResult, json_encode($raw));
        }

        \Illuminate\Support\Facades\File::delete($tmpResultFile);
    }

    public function isBulkVerifySupported(): bool
    {
        return true;
    }

    public function getServiceName(): string
    {
        return 'myEmailVerifier';
    }

    public function getServiceUrl(): string
    {
        return 'https://myemailverifier.com';
    }
}
