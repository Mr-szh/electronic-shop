<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        return true;
    }
    
    public function destroy(User $user, Reply $reply)
    {
        return true;
    }
}