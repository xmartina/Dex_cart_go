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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Support\Facades\Artisan; use Symfony\Component\Console\Output\BufferedOutput; class FinalInstallManager { public function runFinal() { $outputLog = new BufferedOutput(); $this->generateKey($outputLog); $this->publishVendorAssets($outputLog); return $outputLog->fetch(); } private static function generateKey($outputLog) { try { if (!config("\151\156\x73\x74\x61\154\x6c\x65\x72\x2e\146\151\x6e\141\x6c\x2e\153\145\x79")) { goto VFGgh; } Artisan::call("\x6b\x65\x79\x3a\x67\x65\x6e\145\162\x61\164\145", ["\55\x2d\146\x6f\162\x63\145" => true], $outputLog); VFGgh: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function publishVendorAssets($outputLog) { try { if (!config("\x69\x6e\x73\x74\141\154\154\145\162\56\146\151\x6e\x61\x6c\56\x70\165\142\x6c\151\x73\150")) { goto Hmk2O; } Artisan::call("\x76\145\x6e\144\x6f\162\x3a\160\x75\142\x6c\151\163\150", ["\55\55\141\154\x6c" => true], $outputLog); Hmk2O: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function response($message, $outputLog) { return ["\x73\x74\141\x74\x75\x73" => "\x65\x72\162\157\x72", "\155\145\163\163\141\x67\x65" => $message, "\x64\142\x4f\x75\164\x70\165\164\x4c\x6f\147" => $outputLog->fetch()]; } }
