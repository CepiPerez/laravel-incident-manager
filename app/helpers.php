<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


if (! function_exists('sla_expiration')) 
{
    function sla_expiration($created, $sla)
    {
        $created = Carbon::createFromTimeStamp(strtotime("+ $sla HOURS", strtotime($created)));

        $expired = Carbon::now()->gt($created);

        return  [
            'expired' => $expired, 
            'text' => __('main.incidents.' . ($expired? 'expired':'expires'), 
                ['time' => $created->diffForHumans( $expired? [] : ['parts' => 2] )]),
            'hours' => $created->diffInHours()
        ];

    }
}

if (! function_exists('trans_fb')) 
{
    function trans_fb($value)
    {
        return $value!=__($value) ? __($value) : Str::afterLast($value, '.');
    }
}

if (! function_exists('check_img')) 
{
    function check_img($value)
    {
        if (!file_exists($value)) 
            return false;

        $allowedMimeTypes = ['image/jpeg','image/webp','image/png','image/svg+xml'];
        $contentType = mime_content_type($value);
        
        return in_array($contentType, $allowedMimeTypes);
    }
}

if (! function_exists('get_icon_svg')) 
{
    function get_icon_svg($value)
    {
        $array = explode('.', $value);
        $extension = end($array);
        $val = 'txt';

        if (in_array($extension, ['xls', 'xlsx', 'csv'])) 
                $val = 'xls';

            elseif (in_array($extension, ['doc', 'docx'])) 
                $val = 'doc';
            
            elseif (in_array($extension, ['ppt', 'pptx'])) 
                $val = 'ppt';

            elseif (in_array($extension, ['rar', 'zip'])) 
                $val = 'zip';

            elseif ($extension=='pdf') 
                $val = 'pdf';

        return asset("assets/icons/$val.svg");

    }

}

if (! function_exists('get_user_avatar')) 
{
    function get_user_avatar($value)
    {
        if (Storage::exists('profile/'. $value.'.png'))
            return asset('profile/'. $value.'.png');
            
        if (Storage::exists('profile/'. $value.'.jpg'))
            return asset('profile/'. $value.'.jpg');

        if (Storage::exists('profile/'. $value.'.webp'))
            return asset('profile/'. $value.'.webp');
        
        return asset('profile/default.png');

    }

}


