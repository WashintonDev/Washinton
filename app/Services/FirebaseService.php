<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Load Firebase credentials from your .env file
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS'))); // Load credentials

        $this->messaging = $firebase->createMessaging();
    }

    /**
     * Send a notification to a specific Firebase topic.
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data (optional)
     * @return \Kreait\Firebase\Messaging\SendReport
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = [])
    {
        try {
            $dataJson = json_encode($data);
            // Build the message with data only
            $message = CloudMessage::withTarget('topic', $topic) 
                ->withData([ 
                    'title' => $title, 
                    'body' => $body, 
                    'data' => $dataJson // Include the JSON string 
                    ]);
    
            // Send the message
            $response = $this->messaging->send($message);
    
            Log::info('Firebase message sent successfully', ['response' => $response, 'topic' => $topic]);
    
            return $response;
        } catch (\Exception $e) {
            Log::error('Error sending Firebase message', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    

    /**
     * Send a notification to a specific device using its FCM token.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data (optional)
     * @return \Kreait\Firebase\Messaging\SendReport
     */
    public function sendToDevice(string $token, string $title, string $body, array $data = [])
    {
        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($data);

        return $this->messaging->send($message);
    }
}
