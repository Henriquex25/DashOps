<?php

namespace App\Enums;

enum SSHServerCast: string
{
    case Array = 'ssh';
    case Collection = 'collection';
    case Response = 'response';
}
