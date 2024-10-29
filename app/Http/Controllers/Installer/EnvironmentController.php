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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use Illuminate\Http\Request; use Illuminate\Routing\Controller; use Illuminate\Routing\Redirector; use Validator; class EnvironmentController extends Controller { protected $EnvironmentManager; public function __construct(EnvironmentManager $environmentManager) { $this->EnvironmentManager = $environmentManager; } public function environmentMenu() { return view("\x69\156\163\164\141\154\x6c\x65\x72\56\x65\156\166\x69\162\157\x6e\155\x65\156\164"); } public function environmentWizard() { } public function environmentClassic() { $envConfig = $this->EnvironmentManager->getEnvContent(); return view("\151\x6e\x73\x74\x61\154\x6c\145\162\56\145\x6e\166\x69\x72\157\x6e\155\145\x6e\x74\x2d\143\x6c\141\163\x73\151\143", compact("\145\x6e\x76\x43\x6f\156\146\151\x67")); } public function saveClassic(Request $input, Redirector $redirect) { $message = $this->EnvironmentManager->saveFileClassic($input); return $redirect->route("\x49\156\x73\164\x61\x6c\x6c\145\162\56\145\x6e\166\x69\x72\x6f\156\155\x65\x6e\164\x43\x6c\x61\163\x73\151\x63")->with(["\x6d\145\163\163\x61\147\x65" => $message]); } }
