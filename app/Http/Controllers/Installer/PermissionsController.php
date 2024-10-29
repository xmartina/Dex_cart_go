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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\PermissionsChecker; use App\Http\Requests; use Illuminate\Routing\Controller; class PermissionsController extends Controller { protected $permissions; public function __construct(PermissionsChecker $checker) { $this->permissions = $checker; } public function permissions() { $permissions = $this->permissions->check(config("\151\x6e\163\x74\x61\x6c\154\x65\x72\x2e\160\x65\x72\x6d\x69\x73\163\x69\157\156\x73")); return view("\x69\156\x73\164\x61\154\x6c\x65\162\x2e\x70\145\162\x6d\151\x73\163\x69\157\156\x73", compact("\160\x65\162\155\x69\163\163\151\157\156\163")); } }
