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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; use Illuminate\Routing\Controller; class UpdateController extends Controller { use \App\Http\Controllers\Installer\Helpers\MigrationsHelper; public function welcome() { return view("\x69\x6e\x73\x74\x61\x6c\154\x65\x72\x2e\x75\160\144\141\164\x65\56\x77\145\x6c\143\157\155\x65"); } public function overview() { $migrations = $this->getMigrations(); $dbMigrations = $this->getExecutedMigrations(); return view("\x69\156\x73\x74\141\x6c\x6c\145\162\56\165\x70\x64\x61\164\x65\x2e\157\166\x65\x72\x76\x69\145\x77", ["\x6e\165\155\142\145\x72\x4f\x66\125\x70\144\141\x74\145\x73\x50\x65\x6e\x64\x69\156\x67" => count($migrations) - count($dbMigrations)]); } public function database() { $databaseManager = new DatabaseManager(); $response = $databaseManager->migrateAndSeed(); return redirect()->route("\x4c\141\162\141\166\x65\154\x55\x70\x64\x61\x74\145\x72\x3a\72\146\x69\x6e\x61\154")->with(["\x6d\x65\x73\x73\x61\147\x65" => $response]); } public function finish(InstalledFileManager $fileManager) { $fileManager->update(); return view("\x69\x6e\x73\x74\x61\x6c\154\x65\x72\56\x75\x70\x64\x61\164\145\56\146\151\x6e\151\x73\x68\145\144"); } }
