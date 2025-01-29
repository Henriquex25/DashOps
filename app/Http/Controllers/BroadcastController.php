<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BroadcastController extends Controller
{
    public function broadcastMessage(Request $request): Response
    {
        return response()->noContent();
    }
}
