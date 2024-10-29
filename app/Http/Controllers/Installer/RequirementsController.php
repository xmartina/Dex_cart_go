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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\RequirementsChecker; use Illuminate\Routing\Controller; class RequirementsController extends Controller { protected $requirements; public function __construct(RequirementsChecker $checker) { $this->requirements = $checker; } public function requirements() { $phpSupportInfo = $this->requirements->checkPHPversion(config("\151\x6e\163\164\x61\x6c\154\145\162\x2e\x63\x6f\x72\145\56\155\151\156\x50\150\x70\126\145\x72\163\151\157\156"), config("\151\x6e\163\164\141\x6c\154\x65\162\56\x63\157\162\145\56\x6d\141\170\x50\150\x70\x56\x65\162\x73\151\x6f\x6e")); $requirements = $this->requirements->check(config("\x69\x6e\x73\x74\141\154\x6c\x65\162\x2e\x72\145\x71\x75\151\x72\x65\155\x65\156\x74\163")); return view("\151\156\x73\x74\x61\x6c\x6c\x65\x72\56\x72\x65\161\x75\x69\162\145\155\x65\x6e\164\163", compact("\162\145\x71\x75\x69\162\145\155\x65\x6e\164\x73", "\160\x68\x70\123\x75\x70\x70\157\x72\164\111\x6e\146\157")); } }
