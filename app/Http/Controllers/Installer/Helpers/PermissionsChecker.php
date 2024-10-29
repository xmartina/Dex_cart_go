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
 namespace App\Http\Controllers\Installer\Helpers; class PermissionsChecker { protected $results = []; public function __construct() { $this->results["\x70\x65\x72\155\x69\x73\163\x69\157\156\x73"] = []; $this->results["\x65\162\x72\x6f\x72\x73"] = null; } public function check(array $folders) { foreach ($folders as $folder => $permission) { if (!($this->getPermission($folder) >= $permission)) { goto V5vNE; } $this->addFile($folder, $permission, true); goto hE_s2; V5vNE: $this->addFileAndSetErrors($folder, $permission, false); hE_s2: zEKLa: } HbWEk: return $this->results; } private function getPermission($folder) { return substr(sprintf("\x25\157", fileperms(base_path($folder))), -4); } private function addFile($folder, $permission, $isSet) { array_push($this->results["\x70\x65\162\x6d\151\163\163\151\x6f\156\x73"], ["\x66\x6f\154\x64\x65\x72" => $folder, "\160\x65\x72\155\151\x73\x73\x69\x6f\156" => $permission, "\151\x73\x53\x65\164" => $isSet]); } private function addFileAndSetErrors($folder, $permission, $isSet) { $this->addFile($folder, $permission, $isSet); $this->results["\145\x72\162\157\162\x73"] = true; } }
