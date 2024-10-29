<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.14  |
    |              on 2024-07-16 05:48:27              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
/*
* Copyright (C) Incevio Systems, Inc - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Munna Khan <help.zcart@gmail.com>, September 2018
*/
 namespace App\Http\Controllers\Installer\Helpers; class InstalledFileManager { public function create() { $installedLogFile = storage_path("\151\x6e\x73\x74\141\154\x6c\145\144"); $dateStamp = date("\131\x2f\155\57\x64\x20\x68\72\151\72\x73\141"); if (!file_exists($installedLogFile)) { goto GYdBg; } $message = trans("\x69\x6e\x73\x74\x61\154\x6c\145\x72\137\155\145\x73\163\141\147\x65\x73\56\x75\160\x64\141\x74\145\162\56\x6c\157\147\56\163\165\143\x63\145\163\x73\x5f\155\145\163\x73\x61\x67\145") . $dateStamp; file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX); goto jAY46; GYdBg: $message = trans("\151\x6e\x73\164\x61\x6c\154\145\x72\x5f\x6d\x65\163\163\x61\x67\145\x73\x2e\x69\156\163\x74\141\154\154\145\144\x2e\163\x75\x63\x63\145\x73\163\137\x6c\x6f\147\137\155\x65\x73\x73\141\x67\x65") . $dateStamp . "\12"; file_put_contents($installedLogFile, $message); jAY46: return $message; } public function update() { return $this->create(); } }
