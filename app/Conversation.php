<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    //
    protected $fillable = [
        'target_id'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Messages in this conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function target_user(){
        return $this->belongsTo(User::class, 'target_id', 'id');
    }
}
