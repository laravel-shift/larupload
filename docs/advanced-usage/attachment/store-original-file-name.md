---
description: storeOriginalFileName
---

# Store Original File Name

The `storeOriginalFileName` function on the `Attachment` is used to enable or disable the storage of the original name of uploaded files in the database.

Since files may be stored with custom file names based on your preferred <mark style="color:red;">naming method</mark>, storing the original file name in the database can be beneficial for displaying it in your application's UI or elsewhere.



```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mostafaznv\Larupload\Storage\Attachment;
use Mostafaznv\Larupload\Traits\Larupload;

class Media extends Model
{
    use Larupload;

    public function attachments(): array
    {
        return [
            Attachment::make('file')->storeOriginalFileName(true)
        ];
    }
}
```

{% hint style="info" %}
This feature has been available since version <mark style="color:red;">2.2.0</mark>
{% endhint %}

{% hint style="warning" %}
By enabling this property, all your uploading processes will store the original file name in the database. Therefore, you need to add a<mark style="color:red;">`{$name}_file_original_name`</mark> column to all relevant tables.

For new tables, this will be handled by default. However, if you want to use this feature with existing tables, you must create a [new migration](../migrations/add-original-file-name-to-existing-tables.md) file and add the column to those tables.
{% endhint %}



