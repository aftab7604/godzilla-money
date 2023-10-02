<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'User';
$route['404_override'] = 'Auth/page_not_found';
$route['translate_uri_dashes'] = FALSE;

// Cronjob Routes

$route['cronjob/every-minute'] = 'Cronjob/every_minute'; 


// User Auth Routes
$route['logout'] = 'Auth/logout';
$route['login'] = 'Auth/login';
$route['join/(:any)']='Auth/join/$1';
$route['download-apk'] = 'Auth/download_apk';

// Admin Auth Routes
$route['admin/logout'] = 'Auth/admin_logout';
$route['admin/login'] = 'Auth/admin_login';

// Admin Routes
$route['admin'] = 'Admin';
$route['admin/add-package'] = 'Admin/add_package';
$route['admin/package-list'] = 'Admin/package_list';
$route['admin/commission-slab'] = 'Admin/commission_slab';
$route['admin/recharge-requests'] = 'Admin/recharge_requests';
$route['admin/withdraw-requests'] = 'Admin/withdraw_requests';
$route['admin/withdraw-history'] = 'Admin/withdraw_history';
$route['admin/recharge-history'] = 'Admin/recharge_history';
$route['admin/profile'] = 'Admin/admin_profile';
$route['admin/change-password'] = 'Admin/change_password';
$route['admin/site-setting'] = 'Admin/site_setting';
$route['admin/setting/home-slider'] = 'Admin/home_slider_setting';
$route['admin/user-list'] = 'Admin/user_list';
$route['admin/user-detail/(:any)'] = 'Admin/user_detail/$1';
$route['admin/level-setting'] = 'Admin/level_setting';


//User Routes
$route['my/recharge'] = 'User/recharge_request';
$route['share'] = 'User/share';
$route['invest'] = 'User/invest';
$route['trade'] = 'User/trade';
$route['my'] = 'User/my';
$route['my/wallet-address'] = 'User/wallet_address';
$route['my/team'] = 'User/my_team';
$route['my/password-change'] = 'User/password_change';
$route['my/team-commission'] = 'User/team_commission';
$route['my/balance-detail'] = 'User/balance_detail';   
$route['my/withdraw'] = 'User/withdraw';
$route['my/withdraw-history'] = 'User/withdraw_history';






