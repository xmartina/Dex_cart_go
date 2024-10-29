<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    private $model_name;

    public function __construct()
    {
        parent::__construct();

        $this->model_name = trans('app.model.shipping_method');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $this->checkPermission($request, 'view');

        return view('admin.config.shipping-method.index');
    }

    public function activate(Request $request, $id)
    {
        $config = $this->checkPermission($request);

        $shippingMethod = ShippingMethod::findOrFail($id);

        $config->shippingMethods()->syncWithoutDetaching($id);

        switch ($shippingMethod->code) {
            case 'shippo':
                return redirect()->route('admin.setting.shippo.activate');
        }

        return back()->with('error', trans('messages.failed', ['model' => $this->model_name]));
    }


    public function deactivate(Request $request, $id)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $config = $this->checkPermission($request);

        $shippingMethod = ShippingMethod::findOrFail($id);

        $config->shippingMethods()->detach($id);

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }


    /**
     * Check permission
     *
     * @return $config
     */
    private function checkPermission(Request $request, $action = 'update')
    {
        $config = Config::findOrFail($request->user()->merchantId());

        $this->authorize($action, $config);

        return $config;
    }
}
