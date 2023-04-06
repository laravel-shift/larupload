![larupload-banner](https://user-images.githubusercontent.com/7619687/53000850-837af180-343e-11e9-90a1-c30ff435b0b1.png)


[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/mostafaznv/larupload/run-tests.yml?branch=master&label=Build&style=flat-square&logo=github)](https://github.com/mostafaznv/larupload/actions)
[![Codecov branch](https://img.shields.io/codecov/c/github/mostafaznv/larupload/master.svg?style=flat-square&logo=codecov)](https://app.codecov.io/gh/mostafaznv/larupload)
[![Quality Score](https://img.shields.io/scrutinizer/g/mostafaznv/larupload.svg?style=flat-square)](https://scrutinizer-ci.com/g/mostafaznv/larupload)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mostafaznv/larupload.svg?style=flat-square)](https://packagist.org/packages/mostafaznv/larupload)
[![Total Downloads](https://img.shields.io/packagist/dt/mostafaznv/larupload.svg?style=flat-square)](https://packagist.org/packages/mostafaznv/larupload)


# Larupload
**Larupload** will help you upload your files easily. in addition to uploading files, Larupload has interesting features for uploading `video`, `audio` and `image`.
 
in larupload we’ve used the laravel [filesystem](https://laravel.com/docs/filesystem). Thanks to laravel filesystem, it’s easy to switch between "any" desired driver (such as local, sftp, s3, etc.)

----
I develop in an open-source journey 🚀, I wish I lived in an environment where financial situation was fine and I could only focus on the path, but as you may know, life isn't perfect. <br>So if you end up using my packages, please consider making a donation, any amount would go along way and is much appreciated. 🍺

[![Donate](https://mostafaznv.github.io/donate/donate.svg)](https://mostafaznv.github.io/donate)

----

## Some features for Larupload:
- Upload with 2 different strategy: ORM-based and Standalone
- Using different drivers 
- Ability to resize/crop photos and videos
- Ability to create multiple sizes of the videos and images
- Ability to create HTTP Live Streaming (HLS) from video sources
- Built-in support for optimizing images
- Extract the width and height of the image
- Extract width, height and duration of the video
- Extract the duration of the audio
- Extract dominant color from the image and video 
- Automatically create cover image for video files
- Possibility to upload cover for every file
- A specific function for creating database columns when running migration
- Getting the URL of the uploaded file individually or as a set of "defined styles"
- Download response for each style
- Naming files in several ways
- Supports Persian and Arabic for file naming
- Has 2 modes for storage: *heavy* mode and *light* mode
- Queue FFMpeg processes and finish them in background
- Easy to use

## Requirements:
- Laravel 10.* or higher
- PHP 8.1 or higher
- GD library
- FFMPEG


## Installation

1. ##### Install the package via composer:
    ```shell
    composer require mostafaznv/larupload
    ```

2. ##### Publish config, migrations and translations:
    ```shell
    php artisan vendor:publish --provider="Mostafaznv\Larupload\LaruploadServiceProvider"
    ```

3. ##### Create Tables:
    ```shell
    php artisan migrate
    ```

4. ##### Done


## Usage
1. ##### Add the corresponding columns to the desired table
    ```php
    <?php
    
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Mostafaznv\Larupload\Enums\LaruploadMode;
    
    class Uploads extends Migration
    {
        public function up()
        {
            Schema::create('uploads', function (Blueprint $table) {
                $table->increments('id');
                $table->upload('main_file'); // or $table->upload('file', LaruploadMode::HEAVY);
                $table->upload('other_file', LaruploadMode::LIGHT); // or $table->upload('file', LaruploadMode::HEAVY);
                $table->timestamps();
            });
        }
    
        public function down()
        {
            Schema::dropIfExists('uploads');
        }
    }
    ```

2. ##### Add Larupload trait to the model

    ```php
    <?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Mostafaznv\Larupload\Storage\Attachment;
    use Mostafaznv\Larupload\Traits\Larupload;
    use Mostafaznv\Larupload\Enums\LaruploadMode;
    
    class Upload extends Model
    {
        use Larupload;
        
        /**
         * Define Upload Entities
         *
         * @return array
         * @throws \Exception
         */
        public function attachments(): array
        {
            return [
                Attachment::make('main_file'),
                Attachment::make('other_file', LaruploadMode::LIGHT),
            ];
        }
    }
    ```

3. ##### Upload file
    ```php
    $upload = new Upload;
    $upload->main_file = $request->file('file');
    $upload->save();
    ```

## Note 
> - All files are being uploaded in original style. you can add other styles, but the original file is being uploaded as original format.
> - Larupload stores additional information such as name, size, format and... in database. if you create attachment in `heavy` mode, larupload will create a column for each of this information. it's recommended when you want to search/order data by their information. but in `light` mode, all this information will store in a json column.

## Following instructions:
- [Create upload column in the table using migration](#create-upload-column-in-the-table-using-migration)
  - [Create column for heavy mode](#create-column-for-heavy-mode)
  - [Create column for light mode](#create-column-for-light-mode)
- [File upload methods](#file-upload-methods)
    - [Upload by accessor](#upload-by-accessor)
    - [Upload by function](#upload-by-function)
    - [Deleting the existing file](#deleting-the-existing-file-and-the-value-in-the-database)
- [Cover methods](#cover-methods)
    - [Upload cover](#upload-cover)
    - [Update cover](#update-cover)
    - [Delete cover](#delete-cover)
- [Generate download link](#generate-the-download-link)
- [Generate download response](#generate-download-response)
- [Get additional information (Meta)](#get-additional-information-meta)
  - [Get all additional information of the file](#get-all-additional-information-of-the-file)
  - [Get specific meta property by name](#get-specific-meta-property-by-name)
- [Get all attachment urls and meta](#get-all-attachment-urls-and-meta)
- [toArray and toJson](#toarray-and-tojson)
- [Standalone upload](#standalone-upload)
    - [Upload file](#upload-file)
    - [Delete file](#delete-file)
    - [Update cover](#update-cover)
    - [Delete cover](#delete-cover)
    - [Standalone uploader customization](#standalone-uploader-customization)
- [Customization](#customization)
  - [Customization by config file](#customization-by-config-file)
  - [Customization by model's attachments](#customization-by-models-attachments)
- [Queue FFMpeg](#queue-ffmpeg)
  - [Listen Finished Event](#ffmpeg-queue-listen-finished-event)
  - [Relationships](#ffmpeg-queue-relationships)
- [Some extra tricks](#some-extra-tricks)
  - [Set Attribute](#set-attribute)
  - [Get Attribute](#get-attribute)

## Create upload column in the table using migration 
to make creating the columns required by Larupload easier, we have created an artisan command to easily make the columns you need in the table, with the help of the macro feature.

1. ### Create columns for heavy mode
    ```php
    Schema::create('uploads', function (Blueprint $table) {
        $table->increments('id');
        $table->upload('file');
        $table->timestamps();
    });
    ```

2. ### Create columns for light mode
    ```php
    use Mostafaznv\Larupload\Enums\LaruploadMode;
    
    Schema::create('uploads', function (Blueprint $table) {
        $table->increments('id');
        $table->upload('file', LaruploadMode::LIGHT);
        $table->timestamps();
    });
    ```

The difference between `heavy` and `light` mode is in the `number` of table columns, and the way they’re stored.

In heavy mode, a **separate** column is created for **every field** and every data is stored in their own column. this mode is useful when you want to use special queries on the table or use it to sort your data.

But in the light mode, only the **filename** is stored in its own column, and other file information is stored in a **json/string column** named **meta**. This mode is useful to record or display your data.


## File upload methods
1. ### Upload by accessor
    ```php
    $upload->file = $request->file('file');
    ```

2. ### Upload by function
   With the `attach` function, you can upload both file and cover (if needed)

   **arguments of the `attach` function:**
    - First: the `file` that you want to upload *(required field)*
    - Second: `cover` file *(optional)*

   > if you submit the cover file, the priority is to create cover with your uploaded file and it prevents the automatic cover creation by the package

    ```php
    $upload->file->attach($request->file('file'), $request->file('cover'));
    ```

3. ### Deleting the existing file and the value in the database
   You can delete an attached file using `detach` function or assigning `LARUPLOAD_NULL` to it.
    ```php
    $upload->file->detach();
    // or
    $upload->file = LARUPLOAD_NULL;
   
    $upload->save();
    ```

## Cover methods
1. ### Upload cover
   Every cover should be assigned to an original file and if you want a cover, you have to upload it with attach function. 
    ```php
    $upload->file->attach($request->file('file'), $request->file('cover'));
    $upload->save();
    ```

2. ### Update cover
   after uploading a file, you can update the cover whenever you want.

    ```php
    $upload = Upload::findOrFail($id);
    $upload->file->updateCover($request->file('cover'));
    $upload->save();
    ```

3. ### Delete cover
   You can delete an uploaded/generate cover using `detachCover` function.
    ```php
    $upload = Upload::findOrFail($id);
    $upload->file->detachCover();
    $upload->save();
    ```


## Generate the download link 
You can use the following methods to access the uploaded file link:

1. ### Get link for all styles
    
    Code:
    ```php
    dd($upload->file->urls());
    ```
    Output:
    ```php
    [
       "original"  => "http://larupload.site/uploads/uploads/1/file/original/image.png",
       "cover"     => "http://larupload.site/uploads/uploads/1/file/cover/image.jpg",
       "thumbnail" => "http://larupload.site/uploads/uploads/1/file/thumbnail/image.png",
       "meta"      => [
            "name"           => "38792a2e4497b7b64e0a3f79d581c805.jpeg",
            "size"           => 93366,
            "type"           => "image",
            "width"          => 2560,
            "height"         => 1600,
            "duration"       => null,
            "format"         => "png",
            "mime_type"      => "image/png",
            "dominant_color" => "#c5ae0a",
            "cover"          => "38792a2e4497b7b64e0a3f79d581c805.jpg",
       ]
    ];
    ```

2. ### Get link for a particular style
    ```php
    echo $upload->file->url('thumbnail');
    ```
    > If you don’t pass any argument, the link to the original file will be automatically returned.


## Generate download response 
You can use the following methods to generate download response for uploaded file:

Code:
```php
$upload->file->download(); // download original style of attachment
$upload->file->download('cover'); // download cover style of attachment
```

> If you don’t pass any argument, larupload will generate a download response for the original file.


## Get additional information (Meta)

1. ### Get all additional information of the file 
    
    Code:
    ```php
    dd($upload->file->meta());
    ```
    
    Output:
    ```php
    array:9 [▼
      "name"           => "64d65a4e98029c37e7fd510c6e0a34d6.png"
      "size"           => 93366
      "type"           => "image"
      "mime_type"      => "image/png"
      "cover"          => "64d65a4e98029c37e7fd510c6e0a34d6.jpg"
      "width"          => 2560
      "format"         => "png"
      "height"         => 1600
      "duration"       => null
      "dominant_color" => "#c5ae0a"
    ]
    ```
    
    > **type**: returns human-readable file type with this names: `image`, `video`, `audio`, `pdf`, `compressed`, `file` 

2. ### Get specific meta property by name
   
    Code:
    ```php
    echo $upload->file->meta('dominant_color');
    ```
    
    Output:
    ```php
    #c5ae0a
    ```

## Get all attachment urls and meta
using `getAttachments` function, you can retrieve all urls and meta information. if you don't pass any argument to `getAttachments`, it returns urls and meta for all attachments assigned to this model. but if you want this data for a specific attachment, you can pass the name to `getAttachments`.

    Code:
    ```php
    dd($upload->getAttachments())
    ```
    
    Output:
    ```php
    {
        "main_file": {
            "original": "http://larupload.site/storage/media/3/main-file/original/930c04182e6ea99e52fb60ce3f5cf64e.jpg"
            "cover": "http://larupload.site/storage/media/3/main-file/cover/930c04182e6ea99e52fb60ce3f5cf64e.jpg"
            "meta": {
                "name": "930c04182e6ea99e52fb60ce3f5cf64e.jpg"
                "size": 464213
                "type": "image"
                "width": 960
                "height": 640
                "duration": null
                "format": "jpg"
                "cover": "930c04182e6ea99e52fb60ce3f5cf64e.jpg"
                "mimeType": "image/jpeg"
                "dominantColor": "#404040"
            }
        },
        "other_file": {
            "original": "http://larupload.site/storage/media/3/other-file/original/image-7-4748.jpg"
            "cover": "http://larupload.site/storage/media/3/other-file/cover/image-7-4748.jpg"
            "meta": {
                "name": "image-7-4748.jpg"
                "size": 464213
                "type": "image"
                "width": 960
                "height": 640
                "duration": null
                "format": "jpg"
                "cover": "image-7-4748.jpg"
                "mimeType": "image/jpeg"
                "dominantColor": "#404040"
            }
        }
    }
    ```


## toArray and toJson
Larupload returns all attachments automatically on json responses, but you can do it manually by built-in `toArray` and `toJson`  

    Code:
    ```php
    $upload->toArray();
    $upload->toJson();
    ```

## Standalone upload
As you know, larupload works with 2 strategies, ORM-Based and standalone. in the standalone mode, you don't need any model or attachment instance to assign uploaded files to that. you can simply pass base path and original file to larupload and larupload will do it itself.  
1. ### Upload file
   Code: 
    ```php
    use Mostafaznv\Larupload\Larupload;
    
    $file = $request->file('file');
    $cover = $request->file('cover');
   
    $upload = Larupload::init('your/base/path')->upload($file, $cover);
   
    dd($upload);
    ```
   
   Output: 
    ```php
    {
        "original": "http://larupload.site/storage/uploader/original/a3ac7ddabb263c2d00b73e8177d15c8d.mp4",
        "meta": {
            "name": "a3ac7ddabb263c2d00b73e8177d15c8d.mp4"
            "size": 383631
            "type": "video"
            "width": 560
            "height": 320
            "duration": 5
            "format": "mp4"
            "cover": "66ad2a5ebfe7ea349c8b861399c060d8.jpeg"
            "mimeType": "video/mp4"
            "dominantColor": "#e5d2d4"
        }
    }
    ```

2. ### Delete file
    ```php
    $result = Larupload::init('your/base/path')->delete();
    ```

3. ### Update cover
    ```php
    $cover = $request->file('cover');
   
    $upload = Larupload::init('uploaded/base/path')->changeCover($cover);
    ```
   
4. ### Delete cover
    ```php
    $upload = Larupload::init('uploaded/base/path')->deleteCover();
    ```
   
4. ### Standalone uploader customization
    ```php
     $file = $request->file('file');
     $cover = $request->file('cover');
   
     $upload = Larupload::init('path')
            ->namingMethod(LaruploadNamingMethod::HASH_FILE)
            ->image('thumbnail', 1000, 750, LaruploadMediaStyle::CROP)
            ->video('thumbnail', 1000, 750, LaruploadMediaStyle::CROP)         
            ->stream('480p', 640, 480, '64K', '1M')
            ->stream('720p', 1280, 720, '64K', '1M')
            ->upload($file, $cover);
    ```


## Customization
In larupload, we’ve put a lot of effort into making the package more customized. you can customize the package operation in 2 different ways:

1. Using `configuration` file 
2. Using model's attachments


### Customization by config file
- #### Disk
    With this feature, you can set the default disk to upload your files.
    for more information about disks, please read [filesystem](https://laravel.com/docs/filesystem) section in laravel docs
  
    **Note**: Drivers that are supported:
    - local 
    - public
    - sftp 
        > only sftp is supported and we have **no plan** for supporting **ftp**
    - s3 
        > not tested and requires testing; but it doesn't seem to have any problem

- #### Local Disk
    Larupload needs to know your local disk name. when your default disk uses external drivers like sftp, for some reasons larupload needs to use local disk too.

- #### Mode
    There are two modes for storing the uploaded file information in Larupload. `heavy` mode and `light` mode 
    - Heavy mode stores every information and file details in its own column.
    - Light mode stores additional information as `json_encode` in a column named meta. 
        > Note that the selection of each of these modes should fit the type of table created.

- #### SecureIds
  This option allows you to hide the real ID of model records by using a different ID format in the file upload path. This can be useful for security or privacy reasons, as it prevents the real IDs from being easily discoverable.
  <br><br>
  The following ID formats are supported:
  - ULID
  - UUID
  - HASHID (to use this method, you must install the hashids/hashids package)
  - NONE (use real IDs)
  > Note: SecureIds is disabled in standalone mode

- #### With Meta
  With set this value true, meta details will return whenever you retrieve urlsWith set this value true, meta details will return whenever you retrieve urls

- #### Camel Case Response
  By default, larupload returns all meta keys in snake_case style. with enabling this option, we return them cameCase

- #### Hide Table Columns
  Larupload creates multiple columns to work with them. these columns are useless in application-level and even api-level. by default, larupload will hide them from toArray and toJson.

- #### Naming method
    With this feature, you can specify the naming method for files as follows: 
    - **slug**: the name of the uploaded file is converted into slug. to prevent file from caching in different clients, a random number is always added to the end of the filename.
    - **hash_file**: using the `MD5` algorithm, the hash of uploaded file is used as the filename.
    - **time**: upload time is selected as the uploaded file name. 

- #### Lang 
    This feature is used to name files when using the slug template. if you leave this section blank, we will use the application language (available in the `config/app.php` file).

- #### Image processing library 
    You can specify Larupload to use which image processing library to perform crop and resize operations
    
    options:
    - Imagine\Gd\Imagine
    - Imagine\Imagick\Imagine
    - Imagine\Gmagick\Imagine

- #### Generate Cover
    Larupload allows you to automatically generate cover image from the uploaded image or video. With this field, you can enable or disable this feature.

- #### Cover Style
    With this field, you can manage the configuration of the created cover.
    ```php
    'cover-style' => ImageStyle::make(
        name: 'cover',
        width: 500,
        height: 500,
        mode: LaruploadMediaStyle::CROP
    )
    ```

- #### Dominant color
    With this feature, you can extract the dominant color of the image or video.
    > Note that if you disable cover generating, the color extraction of the video will be automatically disabled.

  - #### Dominant Color Quality
    You can set quality to adjust the calculation accuracy of the dominant color. 1 is the highest quality settings, 10 is the default. But be aware that there is a trade-off between quality and speed/memory consumption! If the quality settings are too high (close to 1) relative to the image size (pixel counts), it may **exceed the memory limit** set in the PHP configuration (and computation will be slow).

- #### Keep old files
    By enabling this feature, `prevent` old files from `deleting` while `updating` the database record.

- #### Preserve files flag
    Enabling this feature, `prevent` old files from `being deleted` when the database record is `deleted`.

- #### Optimize Image
    Enabling this feature, you can `optimize` the uploaded image using [spatie/image-optimizer](https://github.com/spatie/image-optimizer)
    > Note: To install optimizer binaries, please read its [documentation](https://github.com/spatie/image-optimizer#optimization-tools)

- #### FFMPEG
    If you keep this section empty, larupload will try to find the FFMPEG path using system environment, but you can manually specify the FFMPEG path this way.
    
    Example:
    ```php
    'ffmpeg' => [
        'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
        'ffprobe.binaries' => '/usr/local/bin/ffprobe'
    ],
    ```
    
- #### FFMPEG Threads
  Specify the number of threads that FFMpeg should use
    
- #### FFMPEG Capture Frame
    Set Capture frame in second
    
    example: null, 0.1, 2
    
    When the value is null, larupload will capture a frame from center of video file.
    
    Example 1:
    ```php
    'ffmpeg' => [
        'capture-frame'  => null,
    ],
    ```
    
    Example 2:
    ```php
    'ffmpeg' => [
        'capture-frame'  => '0.1',
    ],
    ```
    
- #### FFMPEG Log Channel
    Set log channel for ffmpeg process

    Example:
    ```php
    'ffmpeg' => [
        'log-channel' => env('LOG_CHANNEL', 'stack'),
    ],
    ```
    
- #### FFMPEG Timeout
    Set timeout to control ffmpeg max execution time.
    
    Example:
    ```php
    'ffmpeg' => [
        'timeout'  => 60,
    ],
    ```
    
- #### FFMPEG Queue
    Sometimes ffmpeg process is very heavy, so you have to queue process and do it in background
    
    Example:
    ```php
    'ffmpeg' => [
        'queue'  => false,
    ],
    ```
    
- #### Number of max available FFMPEG Queues
    Set maximum Larupload instances that currently are queued.
    > Package Will redirect back an error response if maximum limitation exceeded.
    
    > If you want to ignore this feature and queue uploaded files unlimited, just set 0 for `ffmpeg-max-queue-num`
    
    Example:
    ```php
    'ffmpeg' => [
        'max-queue-num'  => false,
    ],
    ```

### Customization by model's attachments 
In addition to using the config file, that is responsible for Larupload general configuration, you can customize each model by attaching attachment entities.

```php
<?php

namespace App\Models;

use Exception;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mostafaznv\Larupload\Enums\LaruploadImageLibrary;
use Mostafaznv\Larupload\Enums\LaruploadMediaStyle;
use Mostafaznv\Larupload\Enums\LaruploadMode;
use Mostafaznv\Larupload\Enums\LaruploadNamingMethod;
use Mostafaznv\Larupload\Enums\LaruploadStyleMode;
use Mostafaznv\Larupload\Enums\LaruploadSecureIdsMethod;
use Mostafaznv\Larupload\Storage\Attachment;
use Mostafaznv\Larupload\Traits\Larupload;

class Media extends Model
{
    use Larupload;

    /**
     * Define Upload Entities
     *
     * @return array
     * @throws Exception
     */
    public function attachments(): array
    {
        return [
            Attachment::make('main_file')
                ->disk('local')
                ->withMeta(true)
                ->namingMethod(LaruploadNamingMethod::HASH_FILE)
                ->lang('fa')
                ->imageProcessingLibrary(LaruploadImageLibrary::GD)
                ->generateCover(false)
                ->dominantColor(true)
                ->dominantColorQuality(5)
                ->preserveFiles(true)
                ->secureIdsMethod(LaruploadSecureIdsMethod::ULID)
                ->optimizeImage(true)
                ->coverStyle('cover', 400, 400, LaruploadMediaStyle::CROP)
                ->image('thumbnail', 250, 250, LaruploadMediaStyle::AUTO)
                ->image('crop_mode', 1100, 1100, LaruploadMediaStyle::CROP)
                ->image('portrait_mode', 1000, 1000, LaruploadMediaStyle::SCALE_WIDTH)
                ->video('thumbnail', 250, 250, LaruploadMediaStyle::AUTO)
                ->video('crop_mode', 1100, 1100, LaruploadMediaStyle::CROP)
                ->video('portrait_mode', 1000, 1000, LaruploadMediaStyle::SCALE_WIDTH)
                ->video(
                    name: 'auto',
                    width: 300,
                    height: 190,
                    mode: LaruploadMediaStyle::AUTO,
                    format: (new X264)
                        ->setKiloBitrate(1000)
                        ->setAudioKiloBitrate(64)
                )
                ->stream(
                    name: '480p',
                    width: 640,
                    height: 480,
                    format: (new X264)
                        ->setKiloBitrate(3000)
                        ->setAudioKiloBitrate(64)
                )
                ->stream(
                    name: '720p',
                    width: 1280,
                    height:  720,
                    format: (new X264)
                        ->setKiloBitrate(1000)
                        ->setAudioKiloBitrate(64)
                )

            Attachment::make('other_file', LaruploadMode::LIGHT)
                ->stream(
                    name: '480p',
                    width: 640,
                    height: 480,
                    format: (new X264)
                        ->setKiloBitrate(3000)
                        ->setAudioKiloBitrate(64)
                        ->setAudioChannels(1)
                        ->setAudioCodec('libmp3lame')
                ),
        ];
    }
}
```

## Queue FFMpeg
> Note: If you are reading this, we assume you know what is [laravel queue](https://laravel.com/docs/queues). if not, please read laravel's documentation first.

You can enable this feature with `ffmpeg.queue` configuration key. so we just upload original file and then, we start to handle all ffmpeg styles (like crop, resize and stream) in the background.

> If you exceeded maximum amount of available queues, we will redirect back to the previous url with a message to inform your user that queue limitation is exceeded.

### Larupload have some new features with Queue FFMpeg:
- An event to inform you when background job finished.
- Two relationships to show current status of ffmpeg queue process and history of all processes.

### FFMpeg Queue Listen Finished Event
After finish a background job, we will fire an event to inform you that ffmpeg process is done and you can use it now.
So you need to implement an listener to listen this event.

1. ##### Create Listener
    ```php
    php artisan make:listener LaruploadFFMpegQueueNotification 
    ```

2. ##### Register Listener
    You should register your listener in EventServiceProvider
    ```php
    protected $listen = [
        ...

        'Mostafaznv\Larupload\Events\LaruploadFFMpegQueueFinished' => [
            'App\Listeners\LaruploadFFMpegQueueNotification',
        ],
    ];
    ```

2. ##### Handle Event
    In the created listener file, you should get the event.
    ```php
    class LaruploadFFMpegQueueNotification
    {
        public function handle(LaruploadFFMpegQueueFinished $event)
        {
            info("larupload queue finished. id: $event->id, model: $event->model, statusId: $event->statusId");
        }
    }
    ```

### FFMpeg Queue Relationships
In all Eloquent models that are using larupload, you can use these relationships:

- **laruploadQueue**: Return status of latest queued process.
- **laruploadQueues**: Return history of all queued processes.

```php
use App/Upload;

Upload::where('id', 21)->with('laruploadQueue', 'laruploadQueues')->first();
```


## Some notes about stream and style (image/video) functions in larupload
As you know if you want to create hls stream (m3u8) you should use stream function in your attachments function of the model, and if you want to manipulate images or videos, you should handle it with style function.

to use this functions, you should pass `Style` or `Stream` objects to these functions. here we show you how:

#### Image Style

| index | name   | type                 | required | default | description                                                                                                                                       |
|-------|--------|----------------------|----------|---------|---------------------------------------------------------------------------------------------------------------------------------------------------|
| 1     | name   | string               | true     | -       | name of style.  examples: thumbnail, small, ...                                                                                                   |
| 2     | width  | ?int                 | false    | null    | height of the photo or video.                                                                                                                     |
| 3     | height | ?int                 | false    | null    | width of the photo or video                                                                                                                       |
| 4     | mode   | LaruploadMediaStyle  | false    | AUTO    | larupload decides how to deal with the uploaded image. acceptable values for this field are: `FIT`, `AUTO`, `SCALE_WIDTH`, `SCALE_HEIGHT`, `CROP` |

#### Video Style

| index | name     | type                | required | default      | description                                                                                                                                       |
|-------|----------|---------------------|----------|--------------|---------------------------------------------------------------------------------------------------------------------------------------------------|
| 1     | name     | string              | true     | -            | name of style.  examples: thumbnail, small, ...                                                                                                   |
| 2     | width    | ?int                | false    | null         | height of the photo or video.                                                                                                                     |
| 3     | height   | ?int                | false    | null         | width of the photo or video                                                                                                                       |
| 4     | mode     | LaruploadMediaStyle | false    | SCALE_HEIGHT | larupload decides how to deal with the uploaded video. acceptable values for this field are: `FIT`, `AUTO`, `SCALE_WIDTH`, `SCALE_HEIGHT`, `CROP` |
| 5     | format   | X264                | false    | new X264     | Default format is X264. You can set additional options (video/audio kilo bitrate and more) for this format                                        |
| 6     | padding  | bool                | false    | false        | If true, the video will be padded with black color to fit the given dimension                                                                     |

#### Stream
if you want to generate m3u8 files from video sources, you should use `stream`. for now larupload supports hls videos only on stream style.

| index | name             | type   | required | default | description                                                                                                |
|-------|------------------|--------|----------|---------|------------------------------------------------------------------------------------------------------------|
| 1     | name             | string | true     | -       | label for stream quality. highly recommended to use string labels like `720p`                              |
| 2     | width            | int    | true     | -       |                                                                                                            |
| 3     | height           | int    | true     | -       |                                                                                                            |
| 4     | format           | X264   | true     | -       | Default format is X264. You can set additional options (video/audio kilo bitrate and more) for this format |
| 5     | padding          | bool   | false    | false   | If true, the video will be padded with black color to fit the given dimension                              |

## Changelog
Refer to the [Changelog](CHANGELOG.md) for a full history of the project.

----
I develop in a open-source journey 🚀, I wish I lived in an environment where financial situation was fine and I could only focus on the path, but as you may know, life isn't perfect. <br>So if you end up using my packages, please consider making a donation, any amount would go along way and is much appreciated. 🍺

[![Donate](https://mostafaznv.github.io/donate/donate.svg)](https://mostafaznv.github.io/donate)

----

## License
This software is released under [The MIT License (MIT)](LICENSE).

(c) 2018 - 2021 Mostafaznv, All rights reserved.
