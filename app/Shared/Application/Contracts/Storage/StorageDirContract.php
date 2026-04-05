<?php

declare (strict_types=1);
namespace App\Shared\Application\Contracts\Storage;

interface StorageDir {
    public function privatePath():StorageDir;
    public function publicPath():StorageDir;
    public function documents (int $documentId):string;
}
