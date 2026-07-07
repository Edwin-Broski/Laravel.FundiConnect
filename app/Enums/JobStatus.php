<?php

namespace App\Enums;

enum JobStatus: string
{
    case Pending    = 'pending';
    case Accepted   = 'accepted';
    case InProgress = 'in_progress';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';
    case Declined   = 'declined';
}