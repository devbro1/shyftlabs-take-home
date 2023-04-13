<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Announcement",
 *     title="Schema reference for announcement",
 *     @OA\Property(
 *         property="title",
 *         example="",
 *         type="string",
 *
 *     ),
 *     @OA\Property(
 *         property="body",
 *         example="",
 *         description="pure html code for body of announcement. any js or xss will be striped.",
 *         type="string",
 *
 *     )
 * )
 */
class Announcement extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'title',
        'body',
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
            'title' => ['required'],
            'body' => ['required'],
        ];
    }
}
