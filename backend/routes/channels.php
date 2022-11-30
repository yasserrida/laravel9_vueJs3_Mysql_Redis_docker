<?php

use App\Broadcasting\ContratChannel;
use App\Broadcasting\UserChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', UserChannel::class);
