<?php

namespace Emamie\Atom\Auth;

use Encore\Admin\Controllers\AuthController as AuthControllerBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Layout\Content;
use Adldap\Laravel\Facades\Adldap;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Session\Session;

class AuthController extends AuthControllerBase
{

    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        return parent::getLogin();
    }


    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username() => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = Administrator::where('username',$credentials['username'])->first();

        if (parent::guard()->attempt($credentials) ) {

            return parent::sendLoginResponse($request);

        }else if ( !empty($user) && $this->checkUserldap($credentials['username'],$credentials['password'])){
            parent::guard()->login($user);
            return parent::sendLoginResponse($request);

        }else{
            return back()->withInput()->withErrors([
                parent::username() => parent::getFailedLoginMessage(),
            ]);
        }
    }

    /**
     * User setting page.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function getSetting(Content $content)
    {
        return parent::getSetting($content);
    }

    /**
     * Update user setting.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return parent::putSetting();
    }

/*
 * return username and password is correct in ldap
 */
    public function checkUserldap($username,$password)
    {

        if(!env('LDAP_ACTIVE', false)){
            return false;
        }

        $ds = ldap_connect(env('LDAP_HOST'), env('LDAP_PORT')) or die('not connect to active directory');
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);


        $ldaprdn = env('LDAP_DOMAIN') . "\\" . $username;
        $bind = @ldap_bind($ds, $ldaprdn, $password);
        return $bind;


    }

}
