<?php

use Mostafaznv\Larupload\LaruploadEnum;

return [
    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | The default disk for handling file storage. Larupload will use available
    | disks in config/filesystems.php
    |
    */

    'disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    |
    | Larupload work with two modes, light and heavy! in light mode the trait store
    | file name in database and metadata in a json column named as meta.
    | But in heavy mode, it store more columns.
    |
    | Example: light, heavy
    |
    */

    'mode' => LaruploadEnum::HEAVY_MODE,

    /*
    |--------------------------------------------------------------------------
    | With Meta
    |--------------------------------------------------------------------------
    |
    | With set this value enable, meta details will return with file getAttribute (retrieve file urls)
    |
    | Example: true, false
    |
    */

    'with-meta' => true,

    /*
    |--------------------------------------------------------------------------
    | Naming Method
    |--------------------------------------------------------------------------
    |
    | Larupload use some different methods to generate file name
    |
    | Example: slug, hash_file, time
    | Note: Larupload appends an increment number to end of slug to prevent caching for different files with same name
    |
    */

    'naming-method' => LaruploadEnum::SLUG_NAMING_METHOD,
    'lang'          => null,

    /*
    |--------------------------------------------------------------------------
    | Image Processing Library
    |--------------------------------------------------------------------------
    |
    | Larupload can resize or crop image files with power of imagine\imagine
    | library.
    |
    | Example: Imagine\Gd\Imagine, Imagine\Imagick\Imagine, or Imagine\Gmagick\Imagine.
    |
    */

    'image-processing-library' => LaruploadEnum::GD_IMAGE_LIBRARY,

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | An array of image/video sizes defined for the file attachment.
    | Larupload will attempt to format the file upload into the defined style.
    |
    | Example:
    | 'styles' => [
    |   'thumbnail' => [
    |       'height' => 500, // numeric
    |       'width'  => 500, // numeric
    |       'mode'   => 'crop', // string value in: landscape, portrait, crop, exact, auto
    |       'type'   => ['image', 'video'], // array: image, video
    |   ],
    |   'medium' => [
    |       'height' => 1000,
    |       'width'  => 1000,
    |       'mode'   => 'auto',
    |       'type'   => ['image']
    |   ]
    | ]
    */

    'styles' => [],

    /*
    |--------------------------------------------------------------------------
    | Cover Flag
    |--------------------------------------------------------------------------
    |
    | Larupload will generate a cover image from video/image if cover flag is true.
    | Trait will store cover data in cover_file_name, cover_file_size and cover_file_content
    |
    | Note: cover only work in detailed mode
    |
    */

    'generate-cover' => true,

    /*
    |--------------------------------------------------------------------------
    | Cover Style
    |--------------------------------------------------------------------------
    |
    | Larupload will generate a cover image from video/image if cover flag is true.
    | Trait will store cover data in cover_file_name, cover_file_size and cover_file_content
    |
    | Note: cover only work in detailed mode
    |
    */

    'cover-style' => [
        'width'  => 500,
        'height' => 500,
        'mode'   => 'crop',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dominant Color
    |--------------------------------------------------------------------------
    |
    | You can get dominant color from images and videos with this option.
    |
    */

    'dominant-color' => true,

    /*
    |--------------------------------------------------------------------------
    | Keep Old Files Flag
    |--------------------------------------------------------------------------
    |
    | Set this to true in order to prevent older file uploads from being deleted
    | from storage when a record is updated with a new upload.
    |
    */

    'keep-old-files' => false,

    /*
    |--------------------------------------------------------------------------
    | Preserve Files Flag
    |--------------------------------------------------------------------------
    |
    | Set this to true in order to prevent file uploads from being deleted
    | from the file system when an attachment is destroyed.  Essentially this
    | ensures the preservation of uploads event after their corresponding database
    | records have been removed.
    |
    */

    'preserve-files' => false,

    /*
    |--------------------------------------------------------------------------
    | FFMPEG
    |--------------------------------------------------------------------------
    |
    | Larupload can detect your ffmpeg binary path from system environment. but you can set it manually
    | pass from validation.
    |
    | Example: [
    |    'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
    |    'ffprobe.binaries' => '/usr/local/bin/ffprobe',
    | ]
    |
    */

    'ffmpeg' => [],

    /*
    |--------------------------------------------------------------------------
    | FFMPEG Capture Frame
    |--------------------------------------------------------------------------
    |
    | Set Capture frame in second
    |
    | example: null, 0.1, 2
    | When the value is null, larupload will capture a frame from center of video file.
    |
    */

    'ffmpeg-capture-frame' => null,

    /*
    |--------------------------------------------------------------------------
    | FFMPEG Timeout
    |--------------------------------------------------------------------------
    |
    | Set timeout to control ffmpeg max execution time
    |
    */

    'ffmpeg-timeout' => 60,

    /*
    |--------------------------------------------------------------------------
    | FFMPEG Queue
    |--------------------------------------------------------------------------
    |
    | Sometimes ffmpeg process is very heavy, so you have to queue process and do it in background
    | For now, queue is available only for manipulate and stream videos.
    |
    */

    'ffmpeg-queue' => false,

    /*
    |--------------------------------------------------------------------------
    | FFMPEG Max Queue Number
    |--------------------------------------------------------------------------
    |
    | Set maximum Larupload instances that currently are queued.
    | Package Will redirect back an error response if maximum limitation exceeded.
    | If you want to ignore this feature and queue uploaded files unlimited, just set 0 for ffmpeg-max-queue-num
    |
    */

    'ffmpeg-max-queue-num' => 0,
];
