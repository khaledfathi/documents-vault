<?php
declare (strict_types=1);
namespace App\Shared\Domain\Enums\Document;

enum DocumentVisibilityType:string {
    case PUBLIC =  "public";
    case private = "private"; 
     
}