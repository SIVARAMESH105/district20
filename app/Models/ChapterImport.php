<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterImport extends Model
{
    public $table = 'chapters_import';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chapter_name',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';
}
