<?php

namespace App\Http\Middleware;

use App\Helpers\CommonHelper;
use App\Settings;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Students;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Monolog\Handler\LogglyHandler;
use Illuminate\Support\Facades\Route;


class SetLang {
	public function handle($request, Closure $next)
	{
	    $lang = 'en';
	    if(Session::has('lang')) {
	        $lang = Session::get('lang','en');
        }

	    if (isset($lang) && $lang!='') {
	        app()->setLocale($lang);
        }

	    //check installed
	    if(!str_contains($request->path(),'install') && !file_exists(storage_path('installed'))){
	        return redirect('install');
        }

        $restrictedUrls = array(
            'categories.save','categories.update','categories.delete','categories.updateOrder','subcategories.save','subcategories.update',
            'subcategories.delete','products.save','products.update', 'products.updateOrder',
            'products.delete', 'products.multiple_delete', 'products.change', 'products.bulk_upload', 'taxes.save', 'taxes.update',
            'taxes.delete', 'brands.save', 'brands.update', 'brands.delete', 'sellers.save', 'sellers.update', 'sellers.delete', 'sellers.update-status',
            'sellers.updateCommission', 'home_slider_images.save', 'home_slider_images.update', 'home_slider_images.delete', 'promo_code.save', 'promo_code.update', 'promo_code.delete',
            'time_slots.save', 'time_slots.update', 'time_slots.delete', 'units.save', 'units.update', 'units.delete', 'payment_methods.save', 'store_settings.save',
            'mail_settings.save', 'firebase.save', 'popup.save', 'notification_settings.save', 'contact_us.save', 'about_us.save', 'privacy_policy.save', 'privacy_policy_delivery_boy.save',
            'privacy_policy_manager_app.save', 'privacy_policy_seller.save', 'notifications.save',


            'notifications.save', 'notifications.delete', 'sections.save', 'sections.update', 'sections.delete', 'sections.updateOrder', 'offers.save', 'offers.update', 'offers.delete',
            'delivery_boys.save', 'delivery_boys.update', 'delivery_boys.delete', 'delivery_boys.update-status', 'fund_transfers.save', 'cash_collection.save',
            'front_end_policies.save', 'web_settings.save', 'front_end_about.save', 'social_media.save', 'social_media.update', 'social_media.delete', 'seller_wallet_transactions.save',
            'shipping_methods.save', 'seller_commissions.save', 'customers.change',
            'wallet_transactions.save', 'system_users.save', 'system_users.update', 'system_users.delete', 'system_users.change_password', 'withdrawal_requests.save',
            'withdrawal_requests.update', 'withdrawal_requests.delete', 'return_requests.save',
            'return_requests.update', 'return_requests.delete', 'orders.delete', 'orders.deleteItem', 'orders.update_status', 'orders.assign_delivery_boy',
            'orders.update_items_status', 'role.save', 'role.update', 'role.delete', 'media.save', 'media.delete', 'media.multiple_delete', 'seller_commissions.update', 'seller_commissions.delete',
            'cities.save', 'cities.save_boundary', 'cities.update', 'cities.delete', 'faqs.save', 'faqs.update', 'faqs.delete', 'seller.update_status', 'seller.assign_delivery_boy', 'seller.mail_settings.save',
            'delivery_boy.update_status', 'delivery_boy.mail_settings.save'
        );
        $route = Route::getRoutes()->match($request);
        $currentRoute = $route->getName()??'';
        if(in_array($currentRoute ,$restrictedUrls) && env('DEMO_MODE')){
            if(auth()->user() && auth()->user()->id !== 1){
                return CommonHelper::responseError("This function is not available in demo mode!");
            }
        }

		validateAdmin();
        fixVersion();
		return $next($request);
	}
}
