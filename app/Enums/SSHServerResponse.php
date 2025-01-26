<?php

namespace App\Enums;

enum SSHServerResponse: string
{
    case Success = 'success';
    case Error = 'error';
}
