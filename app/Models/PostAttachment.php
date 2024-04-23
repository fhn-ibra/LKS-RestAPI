<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use HasFactory;
    
    protected $table = 'post_attachments';

    protected $fillable = [
        'storage_path',
        'post_id',
    ];

    public function posts(){
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
