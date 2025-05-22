<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('competition.{id}', function ($user, $id) {
    $competition = \App\Models\Competition::find($id);
    if (!$competition) return false;
    
    // Allow access if the user is a participant
    return $competition->participants()->where('user_id', $user->id)->exists();
});