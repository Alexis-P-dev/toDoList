<?php

namespace App\Enum;

enum Importance: string
{
    case URGENTE = 'urgente';
    case IMPORTANTE = 'importante';
    case NORMALE = 'normale';
}