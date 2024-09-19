<?php

namespace App\Console\Commands;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Console\Command;
use Google\Auth\CredentialsLoader;
use Google\Service\FirebaseCloudMessaging;
use Google\Auth\Middleware\AuthTokenMiddleware;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;

class FirebaseNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:firebase-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $fcm_credentials = [
    ];
    private $notification_scopes = ["https://www.googleapis.com/auth/firebase.messaging"];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->sendFirebaseTopicSubscription();
        $this->sendFirebaseNotification();
    }

    public function sendFirebaseTopicSubscription()
    {
        $firebaseClient = $this->getFirebaseHttpClient();

        $topic = 'web-users';

        // Send the notification
        $response = $firebaseClient->post('https://iid.googleapis.com/iid/v1/' . $token . '/rel/topics/' . $topic);
        $this->info($response->getBody()->getContents());
    }

    public function sendFirebaseNotification()
    {
        $firebaseClient = $this->getFirebaseHttpClient();
        // Define the topic to which web devices have subscribed
        $topic = 'web-users';

        // Define the notification payload
        $notificationData = [
            'notification' => [
                'title' => 'Test Web Notification',
                'body'  => 'This is a test message for web browsers',
            ],
            'webpush' => [
                'fcm_options' => [
                    'link' => 'https://yourdomain.com', // Link that opens in the browser
                ],
            ],
            'topic' => $topic
        ];

        // Send the notification
        $response = $firebaseClient->post('/v1/projects//messages:send', [
            'json' => ['message' => $notificationData]
        ]);
        dd($response->getBody()->getContents());
    }

    public function getFirebaseHttpClient(): Client
    {
        $creds = CredentialsLoader::makeCredentials($this->notification_scopes, $this->fcm_credentials);
        $tokenresponse = $creds->fetchAuthToken();
        $accessToken = $tokenresponse['access_token'];

        // create middleware
        $middleware = new AuthTokenMiddleware($creds);
        $stack = HandlerStack::create();
        $stack->push($middleware);

        // create the HTTP client
        $httpClient = new Client([
            'handler' => $stack,
            'base_uri' => 'https://fcm.googleapis.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
                'access_token_auth' => 'true',
                'details' => 'true'
            ],
        ]);
        return $httpClient;
    }
}
