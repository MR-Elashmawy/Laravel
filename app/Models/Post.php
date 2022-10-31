<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;


    protected $fillable = [
        'title',
        'details',
        'slug',
        'image',
        'user_id'

    ]; // end of fillable

    public function user()
    {
        return $this->belongsTo(User::class);
    }// end of user

    public function sluggable(): array
    {
        {
            return [
                'slug' => [
                    'source' => ['title', 'id']
                ],
            ];
        }
    }// end of sluggable
  
} //end of class
