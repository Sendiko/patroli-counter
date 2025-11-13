<?php

namespace App\Enums;

enum ActivityType: string
{
    case MAINTENANCE = 'maintenance';
    case ADMINISTRATION = 'administration';
    case INSPECTION = 'inspection';
    
    // Helper to get formatted names
    public function label(): string {
        return ucfirst($this->value);
    }
}