<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Models\Customer;
use App\Events\Customer\Registered;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Notifications\Auth\SendVerificationEmail as EmailVerificationNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::CUSTOMER_LOGIN;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer')->except('verify');
        $this->middleware('customerCanRegister')->except('verify');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('theme::auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6|confirmed',
            'agree' => 'required',
        ];

        $messages = [];

        if (is_incevio_package_loaded('otp-login')) {
            $rules['phone'] = 'required|string|unique:customers';
        }

        // When recaptcha in configured
        if (config('services.recaptcha.key')) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        if (is_incevio_package_loaded('buyerGroup')) {
            $rules['buyer_group_id'] = 'required|exists:buyer_groups,id';
            $messages['buyer_group_id.required'] = trans('packages.buyer_group_required');
        }

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->back()->with('error', trans('messages.logged_in_as_admin'));
        }

        $this->validator($request->all())->validate();

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'verification_token' => Str::random(40),
        ];

        // Prepare buyerGroup details 
        if (is_incevio_package_loaded('buyerGroup')) {
            $data['buyer_group_application_details'] = (new \Incevio\Package\BuyerGroup\Http\Controllers\ApplicationController())
                ->validateAndGetPreparedApplicationData($request);

            $data['buyer_group_requested_id'] = $request->buyer_group_id;
        }

        // If customer agree to subscribe newsletter or the system set it auto
        if (
            $request->input('subscribe') ||
            !config('system_settings.ask_customer_for_email_subscription')
        ) {
            $data['accepts_marketing'] = 1;
        }

        // When otp-login plugin active
        if (is_incevio_package_loaded('otp-login')) {
            $phone = $request->input('phone');

            send_otp_code($phone, 'customer.register');

            $data['phone'] = $phone;

            Customer::create($data);

            return redirect()->route('phoneverification.notice')->with(['phone_number' => $phone]);
        }

        $customer = Customer::create($data);

        if (is_incevio_package_loaded('zipcode')) {
            $customer->addresses()->create($request->all());
        }

        event(new Registered($customer));

        $this->guard('customer')->login($customer);

        return $this->registered($request, $customer) ?? redirect($this->redirectPath());
    }

    /**
     * Verify the User the given token.
     *
     * @param string|null $token
     * @return \Illuminate\Http\Response
     */
    public function verify($token = null)
    {
        if (!$token) {
            $customer = Auth::guard('customer')->user();

            $customer->verification_token = Str::random(40);

            if ($customer->save()) {
                $customer->notify(new EmailVerificationNotification($customer));

                return redirect()->back()->with('success', trans('auth.verification_link_sent'));
            }

            return redirect()->back()->with('success', trans('auth.verification_link_sent'));
        }

        try {
            $customer = Customer::where('verification_token', $token)->firstOrFail();

            $customer->verification_token = null;

            if ($customer->save()) {
                return redirect()->route('account', 'dashboard')->with('success', trans('auth.verification_successful'));
            }
        } catch (\Exception $e) {
            return redirect()->route('account', 'dashboard')->with('error', trans('auth.verification_failed'));
        }
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $customer
     * @return mixed
     */
    protected function registered(Request $request, Customer $customer)
    {
    }
}
