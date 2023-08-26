<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CoinPairRepository;
use App\Http\Services\LandingService;
use App\Http\Services\Logger;
use App\Http\Services\User2FAService;
use App\Model\Announcement;
use App\Model\CoinPair;
use App\Model\CustomPage;
use App\Model\LandingBanner;
use App\Model\LandingFeature;
use App\Model\LangName;
use App\Model\FaqType;
use App\Model\SocialMedia;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class LandingController extends Controller
{
    private $logger;
    private $coinRepo;
    private $service;
    public function __construct()
    {
        $this->logger = new Logger();
        $this->coinRepo = new CoinPairRepository(CoinPair::class);
        $this->service = new LandingService();
    }

    // common settings
    public function commonSettings()
    {
        $settings = allsetting();
        $data = getUserCurrencyApi();
        $data['app_title'] = $settings['app_title'] ?? __('Tradexpro Exchange');
        $data['copyright_text'] = $settings['copyright_text'] ?? '';
        $data['exchange_url'] = $settings['exchange_url'] ?? '';
        $data['logo'] = show_image(1,'logo');
        $data['login_background'] = !empty($settings['login_logo']) ? asset(path_image().$settings['login_logo']) : asset('assets/user/images/user-content-wrapper-bg.jpg');
        $data['favicon'] = !empty($settings['favicon']) ? asset(path_image().$settings['favicon']) : '';

        $data['cookie_image'] = !empty($settings['cookie_image']) ? asset(path_image().$settings['cookie_image']) : '';
        $data['cookie_status'] = $settings['cookie_status'] ?? '1';
        $data['cookie_header'] = $settings['cookie_header'] ?? '';
        $data['cookie_text'] = $settings['cookie_text'] ?? '';
        $data['cookie_button_text'] = $settings['cookie_button_text'] ?? '';
        $data['cookie_page_key'] = $settings['cookie_page_key'] ?? '';
        $data['live_chat_status'] = $settings['live_chat_status'] ?? '1';
        $data['live_chat_key'] = $settings['live_chat_key'] ?? '';
        $data['swap_status'] = $settings['swap_status'] ?? '1';
        $data['maintenance_mode_status'] = $settings['maintenance_mode_status'] ?? '0';
        $data['maintenance_mode_title'] = $settings['maintenance_mode_title'] ?? '0';
        $data['maintenance_mode_text'] = $settings['maintenance_mode_text'] ?? '0';
        $data['maintenance_mode_img'] = !empty($settings['maintenance_mode_img']) ? asset(path_image().$settings['maintenance_mode_img']) : '';

        $data['currency_deposit_status'] = $settings['currency_deposit_status'] ?? 1;
        $data['currency_deposit_2fa_status'] = $settings['currency_deposit_2fa_status'] ?? 1;
        $data['currency_deposit_faq_status'] = $settings['currency_deposit_faq_status'] ?? 1;
        $data['coin_deposit_faq_status'] = $settings['coin_deposit_faq_status'] ?? 1;
        $data['withdrawal_faq_status'] = $settings['withdrawal_faq_status'] ?? 1;
        $data['LanguageList'] = LangName::where(['status' => STATUS_ACTIVE])->get();
        $data['FaqTypeList'] = FaqType::where(['status' => STATUS_ACTIVE])->get();
        $data['google_analytics_tracking_id'] = $settings['google_analytics_tracking_id'] ?? '';
        $data['seo_image'] = !empty($settings['seo_image']) ? asset(path_image().$settings['seo_image']) : '';
        $data['seo_meta_keywords'] = $settings['seo_meta_keywords'] ?? '';
        $data['seo_meta_description'] = $settings['seo_meta_description'] ?? '';
        $data['seo_social_title'] = $settings['seo_social_title'] ?? '';
        $data['seo_social_description'] = $settings['seo_social_description'] ?? '';
        $data['two_factor_withdraw'] = $settings['two_factor_withdraw'] ?? STATUS_ACTIVE;
        $data['exchange_layout_view'] = $settings['exchange_layout_view'] ?? EXCHANGE_LAYOUT_ONE;
        $data['public_chanel_name'] = env("PUSHER_PUBLIC_CHANEL_NAME") ?? 'tradexpro_public_chanel';
        $data['private_chanel_name'] = env("PUSHER_PRIVATE_CHANEL_NAME") ?? 'tradexpro_private_chanel';
        $data['custom_color'] = $settings['custom_color'] ?? 0;
        $data['theme_color'] = $this->service->userEndColorList();
        $data['two_factor_list'] = User2FAService::twoFactorListCommonSetting($settings);

        return response()->json($data);
    }

    public function index()
    {
        $settings = allsetting();
        $data = [];
        $data['landing_title'] = $settings['landing_title'] ?? __('Buy & Sell Instantly and Hold Cryptocurrency');
        $data['landing_description'] = $settings['landing_description'] ?? __('Tradexpro exchange is such a marketplace where people can trade directly with each other.');

        $data['landing_feature_title'] = $settings['landing_feature_title'] ?? __('Get in touch. Stay in touch.');
        $data['market_trend_title'] = $settings['market_trend_title'] ?? __('Market trend');
        $data['trade_anywhere_title'] = $settings['trade_anywhere_title'] ?? __('Trade Anywhere');
        $data['secure_trade_title'] = $settings['secure_trade_title'] ?? __('Secure trend System');
        $data['customization_title'] = $settings['customization_title'] ?? __('Easy Customization');
        $data['customization_details'] = $settings['customization_details'] ?? __('Tradexpro Exchange is a complete crypto coins exchange platform developed with Laravel. It works via coin payment. There is no need for any personal node, it will connect with a coin payment merchant account. Our system is 100% secure and dynamic. It supports all crypto currency wallets including Coin Payment, Deposit, Withdrawal, Referral system, and whatever you need. ');

        $data['apple_store_link'] = $settings['apple_store_link'] ?? '';
        $data['android_store_link'] = $settings['android_store_link'] ?? '';
        $data['google_store_link'] = $settings['google_store_link'] ?? '';
        $data['macos_store_link'] = $settings['macos_store_link'] ?? '';
        $data['windows_store_link'] = $settings['windows_store_link'] ?? '';
        $data['linux_store_link'] = $settings['linux_store_link'] ?? '';
        $data['api_link'] = $settings['api_link'] ?? '';

        $data['trade_anywhere_left_img'] = !empty($settings['trade_anywhere_left_img']) ? asset(path_image().$settings['trade_anywhere_left_img']) : asset('assets/landing/images/trade-imge.png');
        $data['secure_trade_left_img'] = !empty($settings['secure_trade_left_img']) ? asset(path_image().$settings['secure_trade_left_img']) : asset('assets/landing/images/trade-imge.png');
        $data['landing_banner_image'] = !empty($settings['landing_banner_image']) ? asset(path_image().$settings['landing_banner_image']) : asset('assets/landing/images/landing_banner.svg');

        $data['banner_list'] = $this->bannerListData()['data'];
        $data['announcement_list'] = $this->announcementListData()['data'];
        $data['feature_list'] = $this->featureListData()['data'];
        $data['media_list'] = $this->socialMediaListData()['data'];

        $data['asset_coin_pairs'] = $this->coinRepo->getLandingCoinPairs('asset');
        $data['hourly_coin_pairs'] = $this->coinRepo->getLandingCoinPairs('24hour');
        $data['latest_coin_pairs'] = $this->coinRepo->getLandingCoinPairs('latest');

        $data['copyright_text'] = $settings['copyright_text'];

        return response()->json($data);
    }

    /*
     *
     * banner list
     * also single data
     */
    public function bannerList($id = null)
    {
        $response = $this->bannerListData($id);

        return response()->json($response);
    }

    public function bannerListData($id = null)
    {
        $response = ['success' => false, 'message' => __('Something went wrong'), 'data' => []];
        try {
            if (isset($id)) {
                $item = LandingBanner::where(['status' => STATUS_ACTIVE, 'slug' => $id])->first();
                if (isset($item)) {
                    $item->image = !empty($item->image) ? asset(path_image().$item->image) : '';

                    $response = [
                        'success' => true,
                        'message' => __('Data get successfully'),
                        'data' => $item
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('No data found'),
                        'data' => []
                    ];
                }
            } else {
                $items = LandingBanner::where(['status' => STATUS_ACTIVE])->orderBy('id', 'desc')->get();
                if (isset($items[0])) {
                    foreach ($items as $item) {
                        $item->image = !empty($item->image) ? asset(path_image().$item->image) : '';
                    }
                    $response = [
                        'success' => true,
                        'message' => __('Data get successfully'),
                        'data' => $items
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('No data found'),
                        'data' => []
                    ];
                }
            }
        } catch (\Exception $e) {
            $this->logger->log('bannerList',$e->getMessage());
        }
        return $response;
    }
    /*
     *
     * announcement list
     * also single data
     */
    public function announcementList($id = null)
    {
        $response = $this->announcementListData($id);

        return response()->json($response);
    }

    public function announcementListData($id = null)
    {
        $response = ['success' => false, 'message' => __('Something went wrong'), 'data' => []];
        try {
            if (isset($id)) {
                $item = Announcement::where(['status' => STATUS_ACTIVE, 'slug' => $id])->first();
                if (isset($item)) {
                    $item->image = !empty($item->image) ? asset(path_image().$item->image) : '';

                    $response = [
                        'success' => true,
                        'message' => __('Data get successfully'),
                        'data' => $item
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('No data found'),
                        'data' => []
                    ];
                }
            } else {
                $items = Announcement::where(['status' => STATUS_ACTIVE])->orderBy('id', 'desc')->get();
                if (isset($items[0])) {
                    foreach ($items as $item) {
                        $item->image = !empty($item->image) ? asset(path_image().$item->image) : '';
                    }
                    $response = [
                        'success' => true,
                        'message' => __('Data get successfully'),
                        'data' => $items
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('No data found'),
                        'data' => []
                    ];
                }
            }
        } catch (\Exception $e) {
            $this->logger->log('Announcement',$e->getMessage());
        }
        return $response;
    }

    /*
     *
     * feature list
     * also single data
     */
    public function featureList($id = null)
    {
        $response = $this->featureListData($id);

        return response()->json($response);
    }

     public function featureListData($id = null)
        {
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' => []];
            try {
                if (isset($id)) {
                    $item = LandingFeature::where(['status' => STATUS_ACTIVE, 'id' => $id])->first();
                    if (isset($item)) {
                        $item->feature_icon = !empty($item->feature_icon) ? asset(path_image().$item->feature_icon) : '';

                        $response = [
                            'success' => true,
                            'message' => __('Data get successfully'),
                            'data' => $item
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => __('No data found'),
                            'data' => []
                        ];
                    }
                } else {
                    $items = LandingFeature::where(['status' => STATUS_ACTIVE])->orderBy('id', 'desc')->get();
                    if (isset($items[0])) {
                        foreach ($items as $item) {
                            $item->feature_icon = !empty($item->feature_icon) ? asset(path_image().$item->feature_icon) : '';
                        }
                        $response = [
                            'success' => true,
                            'message' => __('Data get successfully'),
                            'data' => $items
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => __('No data found'),
                            'data' => []
                        ];
                    }
                }
            } catch (\Exception $e) {
                $this->logger->log('featureList',$e->getMessage());
            }
            return $response;
        }

    /*
     *
     * social media list
     * also single data
     */
    public function socialMediaList($id = null)
    {
        $response = $this->socialMediaListData($id);

        return response()->json($response);
    }

    public function socialMediaListData($id = null)
    {
        $response = ['success' => false, 'message' => __('Something went wrong'), 'data' => []];
        try {
            if (isset($id)) {
                $item = SocialMedia::where(['status' => STATUS_ACTIVE, 'id' => $id])->first();
                if (isset($item)) {
                    $item->media_icon = !empty($item->media_icon) ? asset(path_image().$item->media_icon) : '';

                    $response = [
                        'success' => true,
                        'message' => __('Data get successfully'),
                        'data' => $item
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('No data found'),
                        'data' => []
                    ];
                }
            } else {
                $items = SocialMedia::where(['status' => STATUS_ACTIVE])->orderBy('id', 'desc')->get();
                if (isset($items[0])) {
                    foreach ($items as $item) {
                        $item->media_icon = !empty($item->media_icon) ? asset(path_image().$item->media_icon) : '';
                    }
                    $response = [
                        'success' => true,
                        'message' => __('Data get successfully'),
                        'data' => $items
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('No data found'),
                        'data' => []
                    ];
                }
            }
        } catch (\Exception $e) {
            $this->logger->log('social media',$e->getMessage());
        }
        return $response;
    }

    // recaptcha settings
    public function reCaptchaSettings()
    {
        $settings =  allsetting();
        $data['google_recapcha'] = $settings['google_recapcha'];
        $data['NOCAPTCHA_SECRET'] = $settings['NOCAPTCHA_SECRET'];
        $data['NOCAPTCHA_SITEKEY'] = $settings['NOCAPTCHA_SITEKEY'];
        $response = ['success' => true, 'message' => __('Success'), 'data' => $data];
        return response()->json($response);
    }

    // get custom page list
    public function getCustomPageList($type=null)
    {
        try {
            if (is_null($type)) {
                $list = CustomPage::where(['status' => STATUS_ACTIVE])->orderBy('data_order','ASC')->get();
            } else {
                $list = CustomPage::where(['status' => STATUS_ACTIVE, 'type' => $type])->orderBy('data_order','ASC')->get();
            }
            $response = ['success' => true, 'message' => __('Success'), 'data' => $list];
        } catch (\Exception $e) {
            $this->logger->log('getCustomPageList', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' => []];
        }
        return $response;
    }
    // get custom page details
    public function getCustomPageDetails($slug)
    {
        try {
            $item = CustomPage::where(['key' => $slug, 'status' => STATUS_ACTIVE])->first();
            if (isset($item)) {
                $response = ['success' => true, 'message' => __('Success'), 'data' => $item];
            } else {
                $response = ['success' => false, 'message' => __('No data found'), 'data' => (object)[]];
            }
        } catch (\Exception $e) {
            $this->logger->log('getCustomPageDetails', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' => (object)[]];
        }
        return $response;
    }
}
