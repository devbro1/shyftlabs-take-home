<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    use BaseModel;

    public static function getValidationRules(array $values, self $model = null)
    {
        return [];
    }

    public function getFileAbselutePath()
    {
        return storage_path('app/'.$this->path);
    }

    public static function saveToDB($path, $filename = null): File
    {
        if (!Storage::exists($path)) {
            throw new \Exception('file does not exists');
        }
        $details = pathinfo(storage_path('app/'.$path));
        $file = new File();
        $file->filename = $filename ?? $details['basename'];
        $file->mimetype = Storage::mimeType($path);
        $file->extension = $details['extension'];
        $file->size = Storage::size($path);
        $file->path = $path;
        $file->save();

        return $file;
    }
}
