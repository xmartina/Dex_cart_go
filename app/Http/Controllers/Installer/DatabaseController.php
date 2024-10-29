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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use Exception; use Illuminate\Routing\Controller; use Illuminate\Support\Facades\DB; class DatabaseController extends Controller { private $databaseManager; public function __construct(DatabaseManager $databaseManager) { $this->databaseManager = $databaseManager; } public function database() { if ($this->checkDatabaseConnection()) { goto jTZwL; } return redirect()->back()->withErrors(["\x64\141\164\x61\142\141\163\x65\x5f\x63\x6f\156\x6e\x65\x63\x74\x69\x6f\156" => trans("\x69\156\x73\x74\x61\x6c\154\145\x72\x5f\155\145\163\x73\x61\x67\145\163\x2e\x65\156\166\x69\x72\x6f\x6e\x6d\145\156\x74\x2e\167\x69\172\141\162\x64\56\x66\x6f\x72\x6d\x2e\x64\142\x5f\x63\157\x6e\156\145\x63\164\x69\x6f\x6e\x5f\146\141\151\154\145\144")]); jTZwL: ini_set("\x6d\141\170\x5f\145\x78\145\143\165\x74\x69\x6f\156\137\x74\151\155\x65", 600); $response = $this->databaseManager->migrateAndSeed(); return redirect()->route("\x49\156\163\164\141\x6c\x6c\145\x72\x2e\146\x69\156\x61\154")->with(["\155\145\163\x73\141\x67\x65" => $response]); } private function checkDatabaseConnection() { try { DB::connection()->getPdo(); return true; } catch (Exception $e) { return false; } } }
