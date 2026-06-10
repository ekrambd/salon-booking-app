<?php

use Carbon\Carbon;
use App\Models\Withdrawsetting;
use App\Models\StaffService;
use Firebase\JWT\JWT;

function user(){
    $user = auth()->user();
    return $user;
}

function checkRole($role)
{
    if(user()->role !== $role){
        return false;
    }
    return true;
}

function getSpecialService($id)
{
    $data = StaffService::join('services', 'staff_services.service_id', '=', 'services.id')
    ->select('services.id', 'services.name as service_name')
    ->where('staff_services.staff_id', $id)
    ->orderByDesc('staff_services.is_special')
    ->first();

    return $data;
}

if (!function_exists('storeFile')) {
    function storeFile($file, $filePath, $prefix)
    {
        // Define the directory path
        # $filePath = 'files/images/country'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        # $fileName = uniqid('flag_', true) . '.' . $file->getClientOriginalExtension();
        $fileName = uniqid($prefix, true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
}
if (!function_exists('updateFile')) {
    function updateFile($file, $filePath, $prefix, $oldFilePath = null)
    {
        // Delete the old file if it exists
        deleteOldFile($oldFilePath);

        // Store path & file name in the database
        $path = storeFile($file, $filePath, $prefix);
        return $path;
    }
}
if (!function_exists('deleteOldFile')) {
    function deleteOldFile($oldFilePath)
    {
        // TODO: ensure from database
        if (!empty($oldFilePath)) { # ensure from database
            $oldFullFilePath = public_path($oldFilePath); // Use without prepending $filePath
            if (file_exists($oldFullFilePath)) {
                unlink($oldFullFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFullFilePath]);
                return false;
            }
        }
    }
}
if (!function_exists('timeFormat')) {
    function timeFormat($time)
    {
        return Carbon::parse($time)->format('h:i a');
    }
}

function getTimeStamP($request)
{
    $data = $request->booking_date.$request->booking_time;
    return strtotime($data);
}

function withdrawSetting()
{
    $data = Withdrawsetting::find(1);
    return $data;
}

function sendPush($title,$body,$device_token)
{
    $fcmData = [
        'success' => "true",
        'message' => $body,
    ];

    $serviceAccount = json_decode(
        file_get_contents(public_path('fcm/barber-app-f388d-firebase-adminsdk-fbsvc-ef480a7ffc.json')),
        true
    );

    $now = time();
    $jwt = JWT::encode([
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600
    ], $serviceAccount['private_key'], 'RS256');

    // Get Access Token
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ])
    ]);

    $tokenResponse = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($tokenResponse, true);
    if (!isset($tokenData['access_token'])) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to get FCM access token'
        ], 500);
    }

    $accessToken = $tokenData['access_token'];

    // FCM Payload
    $payload = [
        'message' => [
            'token' => $device_token,
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
            'data' => $fcmData
        ]
    ];

    $fcmUrl = 'https://fcm.googleapis.com/v1/projects/' .
        $serviceAccount['project_id'] . '/messages:send';

    $ch = curl_init($fcmUrl);
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    curl_exec($ch);
    curl_close($ch);
}

function timezones()
{
    $timezones = \DateTimeZone::listIdentifiers();
    return $timezones;
}