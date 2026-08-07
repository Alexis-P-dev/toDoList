<?php

namespace App\Enum;

enum Statut: string
{
    case A_FAIRE = 'a_faire';
    case EN_COURS = 'en_cours';
    case TERMINE = 'termine';
}