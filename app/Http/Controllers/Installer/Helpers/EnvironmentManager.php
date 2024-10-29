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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Http\Request; class EnvironmentManager { private $envPath; private $envExamplePath; public function __construct() { $this->envPath = base_path("\56\x65\x6e\166"); $this->envExamplePath = base_path("\56\x65\156\166\56\145\x78\x61\x6d\x70\154\x65"); } public function getEnvContent() { if (file_exists($this->envPath)) { goto gjZsB; } if (file_exists($this->envExamplePath)) { goto BHuVA; } touch($this->envPath); goto pwT5q; BHuVA: copy($this->envExamplePath, $this->envPath); pwT5q: gjZsB: return file_get_contents($this->envPath); } public function getEnvPath() { return $this->envPath; } public function getEnvExamplePath() { return $this->envExamplePath; } public function saveFileClassic(Request $input) { $message = trans("\x69\x6e\163\164\141\x6c\154\145\162\137\155\145\x73\x73\141\147\x65\x73\x2e\145\156\x76\151\x72\x6f\x6e\x6d\x65\x6e\x74\x2e\163\165\143\143\145\163\x73"); try { file_put_contents($this->envPath, $input->get("\145\156\x76\x43\157\156\146\151\147")); } catch (Exception $e) { $message = trans("\151\156\163\x74\x61\154\154\x65\162\x5f\155\x65\163\x73\x61\x67\x65\x73\56\145\156\x76\151\x72\157\156\x6d\145\156\164\x2e\x65\x72\x72\157\162\163"); } return $message; } }
