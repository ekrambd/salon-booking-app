<?php

use Carbon\Carbon;
use App\Models\Withdrawsetting;
use App\Models\StaffService;


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