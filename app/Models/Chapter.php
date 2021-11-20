<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public $table = 'chapters';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chapter_name',
        'chapter_image',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';
    
    /**
     * This model function is over ride default all function
     *
     * @param  array|mixed  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author Techaffinity:vinothcl
     */
    public static function all($columns = ['*'])
    {
        return static::query()->where('is_active', 1)->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }
}
