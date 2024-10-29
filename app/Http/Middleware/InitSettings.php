<?php
namespace App\Http\Middleware;
use App\Helpers\ListHelper;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class InitSettings
{
	public function handle($request, Closure $next)
	{
		if (!$request->is("\x69\x6e\163\164\x61\x6c\x6c\52")) {
			goto KHv1s;
		}
		return $next($request);
		KHv1s:setSystemConfig();
		View::addNamespace("\x74\150\145\155\145", theme_views_path());
		if (!Auth::guard("\x77\x65\x62")->check()) {
			goto eosNo;
		}
		if (!$request->session()->has("\x69\155\160\x65\x72\163\x6f\156\x61\x74\145\144")) {
			goto ogm2Y;
		}
		Auth::onceUsingId($request->session()->get("\151\x6d\160\145\x72\x73\157\x6e\x61\x74\x65\144"));
		ogm2Y:
		if ($request->is("\141\x64\155\151\x6e\x2f\52") || $request->is("\141\x63\x63\x6f\x75\156\164\x2f\x2a")) {
			goto ofhtB;
		}
		return $next($request);
		goto E8eiS;
		ofhtB:
		if ($request->is("\141\144\155\151\156\57\x73\x65\x74\x74\151\156\147\x2f\x73\x79\x73\164\x65\155\x2f\52")) {
			goto VVvFk;
		}
		$this->can_load();
		VVvFk:E8eiS:$user = Auth::guard("\x77\145\142")->user();
		if (!(!$user->isFromPlatform() && $user->merchantId())) {
			goto sQJey;
		}
		setShopConfig($user->merchantId());
		sQJey:$permissions = Cache::remember(
			"\160\145\x72\155\151\163\x73\151\x6f\x6e\163\x5f" . $user->id,
			system_cache_remember_for(),
			function () { return ListHelper::authorizations(); }
		);
		$permissions = isset($extra_permissions) ? array_merge($extra_permissions, $permissions) : $permissions;
		config()->set("\160\145\x72\155\x69\163\163\151\157\x6e\x73", $permissions);
		if (!$user->isSuperAdmin()) {
			goto r9HKC;
		}
		$slugs = Cache::remember("\163\x6c\x75\x67\x73", system_cache_remember_for(), function () { return ListHelper::slugsWithModulAccess(); });
		config()->set("\x61\165\x74\x68\x53\154\x75\147\163", $slugs);
		r9HKC:eosNo:return $next($request);
	}
	private function can_load()
	{
		yEWtS:incevioAutoloadHelpers(getMysqliConnection());
	}
}
