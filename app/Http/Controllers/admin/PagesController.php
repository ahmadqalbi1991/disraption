<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Article;
use Validator;
use Illuminate\Http\Request;
use App\ContactUsQueries;
use App\Models\ContactUsSetting;
use App\Models\CountryModel;
use App\Models\SettingsModel;
use App\Models\ProfileBio;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Settings;

use function PHPUnit\Framework\isNull;

class PagesController extends Controller
{

    // Reschedule policies, storing it here as cache for the request so it can be used in the same request without multiple db calls
    protected static $reschedulePolicies = null;
    protected static $location = null;
    protected static $c_policy = null;


    public function index(Request $request)
    {

        if (!get_user_permission('cms_pages', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "CMS Pages";
        $cms_pages = Article::orderByRaw('COALESCE(updated_at, created_at) DESC')->get();

        return view('admin.cms_pages.index', compact('cms_pages', 'page_heading'));
    }

    public function create(Request $request)
    {

        if (!get_user_permission('cms_pages', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Add New Page";
        $cms_page = new Article();
        $cms_page->status = 1;
        return view('admin.cms_pages.form', compact('page_heading', 'cms_page'));
    }
    public function edit(Request $request, $id)
    {

        if (!get_user_permission('cms_pages', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Update Page";
        $cms_page = Article::where("id", $id)->first();
        return view('admin.cms_pages.form', compact('page_heading', 'cms_page'));
    }

    public function save(Request $request)
    {
        $status  = "0";
        $message = "";
        $o_data  = [];
        $errors  = [];
        $redirectUrl = '';
        $id      = $request->id;
        $rules   = [
            'title_en'      => 'required',
            'desc_en'       => 'required',
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'title_en.required' => 'Title required',
                'desc_en.required' => 'Description Engish required',

            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            if ($request->id != null) {
                $cms_page = Article::find($request->id);
            } else {
                $cms_page = new Article();
            }
            $cms_page->status     = $request->status == 1 ? 1 : 0;
            $cms_page->title_en     = $request->title_en;
            $cms_page->title_ar     = $request->title_ar;
            $cms_page->desc_en = $request->desc_en;
            $cms_page->desc_ar = $request->desc_ar;
            $cms_page->save();
            $status = "1";
            $message = 'Record has been saved successfully';
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }


    public function delete($id)
    {
        $record = Article::find($id);
        $status = "0";
        $message = "Page removal failed";
        if ($record) {
            $record->delete();
            $status = "1";
            $message = "Page removed successfully";
        }

        echo json_encode(['status' => $status, 'message' => $message]);
    }


    public static function getAllSettings()
    {
        $settings = Setting::get();
        $settings = $settings->pluck('meta_value', 'meta_key')->toArray();
        return $settings;
    }

    public static function getSettingsFields()
    {

        return [
            'company_name' => array('label' => 'Company Name', 'type' => 'text', 'isRequired' => false, 'class' => 'col-md-6', 'validator' => 'nullable|string'),
            'company_address' => array('label' => 'Company Address', 'type' => 'text', 'isRequired' => false, 'class' => 'col-md-6', 'validator' => 'nullable|string'),
            'email' => array('label' => 'Email', 'type' => 'email', 'isRequired' => false, 'class' => 'col-md-6', 'validator' => 'nullable|email'),
            'website' => array('label' => 'Website', 'type' => 'text', 'isRequired' => false, 'class' => 'col-md-6', 'validator' => 'nullable|url'),
            'tax_percentage' => array('label' => 'Tax Percentage', 'type' => 'number', 'isRequired' => false, 'class' => 'col-md-6', 'validator' => 'nullable|numeric'),
            'scl_twitter' => array('label' => 'Twitter', 'type' => 'text', 'isRequired' => false, 'class' => 'col-md-4', 'validator' => 'nullable|url'),
            'scl_facebook' => array('label' => 'Facebook', 'type' => 'text', 'isRequired' => false, 'class' => 'col-md-4', 'validator' => 'nullable|url'),
            'scl_instagram' => array('label' => 'Instagram', 'type' => 'text', 'isRequired' => false, 'class' => 'col-md-4', 'validator' => 'nullable|url'),
            'scl_linkedin' => array('label' => 'Linkedin', 'type' => 'text', 'isRequired' => false,     'class' => 'col-md-4', 'validator' => 'nullable|url'),
        ];
    }

    public function settings()
    {
        if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Settings";


        $countries = CountryModel::getCountries();

        // $settings have the rows which have two columns meta_key and meta_value, so ready the array which key is meta_key and value is meta_value
        $settings = PagesController::getAllSettings();

        $getSettingValue = function ($key) use ($settings) {
            return isset($settings[$key]) ? $settings[$key] : '';
        };

        $fields = self::getSettingsFields();

        // Loop through the fields and set the value from the settings
        foreach ($fields as $key => $field) {
            $fields[$key]['value'] = $getSettingValue($key);
        }


        return view('admin.settings.create', compact('page_heading', 'getSettingValue', 'countries', 'fields'));
    }

    public function setting_store(Request $request)
    {

        // Get all setting fields
        $fields = self::getSettingsFields();

        // Allowed meta keys to update on db
        $allowedKeys = ['whatsapp_phone', 'whatsapp_dialcode'];

        // Append all fields keys to the allowedKeys
        foreach ($fields as $key => $field) {
            $allowedKeys[] = $key;
        }

        $rules = [
            'whatsapp_phone' => 'numeric',
            'whatsapp_dialcode' => 'numeric',
        ];


        // Loop through the fields and set the validator
        foreach ($fields as $key => $field) {
            if (isset($field['validator'])) {
                $rules[$key] = $field['validator'];
            }
        }

        // Validate
        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            // return with  $returnResponse

            return response()->error("Validation failed", $validator->errors());
        }


        DB::transaction(function () use ($request, $allowedKeys) {
            $settingsToUpdate = [];
            $settingsToCreate = [];

            foreach ($request->all() as $key => $value) {
                if (in_array($key, $allowedKeys)) {

                    $value == is_null($value) ? '' : $value;

                    $settingsToUpdate[$key] = $value;
                }
            }

            // Fetch existing settings
            $existingSettings = Setting::whereIn('meta_key', array_keys($settingsToUpdate))->get();

            // Update existing settings individually
            foreach ($existingSettings as $setting) {
                $metaKey = $setting->meta_key;
                if (isset($settingsToUpdate[$metaKey])) {
                    $setting->meta_value = $settingsToUpdate[$metaKey];
                    $setting->save();
                }
            }

            // Determine settings to create
            $existingKeys = $existingSettings->pluck('meta_key')->toArray();
            foreach ($settingsToUpdate as $key => $value) {
                if (!in_array($key, $existingKeys)) {

                    $value = is_null($value) ? '' : $value;

                    $settingsToCreate[] = [
                        'meta_key' => $key,
                        'meta_value' => $value
                    ];
                }
            }

            // Bulk insert new settings
            if (!empty($settingsToCreate)) {
                Setting::insert($settingsToCreate);
            }
        });



        Cache::forget('settings');

        return response()->success("Settings saved successfully");
    }

    public static function getReschedulePolicies()
    {
        if (is_null(self::$reschedulePolicies)) {
            $setting = Setting::where('meta_key', 'return_policies_hours')->first();
            self::$reschedulePolicies = $setting ? json_decode($setting->meta_value, true) : null;
        }

        return self::$reschedulePolicies;
    }


    public static function getCPolicy()
    {
        if (is_null(self::$c_policy)) {
            $setting = Setting::where('meta_key', 'c_policy')->first();
            self::$c_policy = $setting ? $setting->meta_value : null;
        }

        return self::$c_policy;
    }

    public static function getLocation()
    {
        if (is_null(self::$location)) {
            $setting = Setting::where('meta_key', 'cms_location')->first();
            self::$location = $setting ? json_decode($setting->meta_value, true) : array(
                'latitude' => null,
                'longitude' => null,
                'location_name' => null,
            );
        }

        return self::$location;
    }

    public function reschedulePolicyView()
    {
        if (!get_user_permission('cms_rechedule_policy', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Reschedule Policy";
        $r_policy = Setting::where('meta_key', 'return_policies_hours')->first();
        // try to decode the r_policy json if not then set empty array
        $return_policies = null;
        if ($r_policy) {
            try {
                $return_policies = json_decode($r_policy->meta_value);
            } catch (\Exception $e) {
                // Handle the exception here
            }
        }

        // if $return_policies is null then set empty array
        if (!$return_policies) {
            $return_policies = [];
        }

        return view('admin.reschedule_policy.edit', compact('page_heading', 'return_policies'));
    }

    public function rechedulePolicy_store(Request $request)
    {

        // validate the return_policies should be array
        $validator = VAlidator::make($request->all(), [
            'return_policies' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation failed", $validator->errors());
        }

        // if no $request->return_policies then set empty array
        if (!$request->return_policies) {
            $request->return_policies = [];
        }

        // Loop through the return_policies and add to the array with the 0 index and keep incrementing
        $iindex = 0;
        $formated_return_policies = [];
        foreach ($request->return_policies as $policy) {

            $policy = json_decode($policy, true);

            $formated_return_policies[$iindex] = [
                'hours' => $policy["hours"],
                'amount' => $policy["amount"],
            ];

            $iindex++;
        }

        // Insert or update to setting
        Setting::updateOrCreate(
            ['meta_key' => 'return_policies_hours'], // Condition to find the record
            ['meta_value' => json_encode($formated_return_policies)] // Data to update or create
        );

        // Return success
        return response()->success("Saved successfully");
    }

    public function locationView (Request $request) {

        if (!get_user_permission('cms_location', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Location";
        $location = Setting::where('meta_key', 'cms_location')->first();
        $location = $location ? json_decode($location->meta_value, true) : array(
            'latitude' => null,
            'longitude' => null,
            'location_name' => null,
        );

        $lattitude = $location["latitude"];
        $longitude = $location["longitude"];
        $location_name = $location["location_name"];

        return view('admin.cms_location.edit', compact('page_heading', 'lattitude', 'longitude', 'location_name'));

    }

    public function location_store (Request $request) {

        $validator = Validator::make($request->all(), [
            'location' => 'required',
            'location_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation failed", $validator->errors());
        }

         // Extract the long and lat from the location
         $lat = "";
         $long = "";
         if ($request->location) {
             $location = explode(",", $request->location);
             $lat = $location[0];
             $long = $location[1];
         }

        $location = [
            'latitude' => $lat,
            'longitude' => $long,
            'location_name' => $request->location_name,
        ];

        Setting::updateOrCreate(
            ['meta_key' => 'cms_location'], // Condition to find the record
            ['meta_value' => json_encode($location)] // Data to update or create
        );

        return response()->success("Location saved successfully");
    }

    public function cancellationView (Request $request) {

        if (!get_user_permission('cms_cancellation_policy', 'u')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "Cancellation Policy";
        $meta = Setting::where('meta_key', 'cms_cancellation_policy')->first();
        $cancellationPolicy = $meta ? $meta->meta_value : "";

        return view('admin.cms_cancellation_policy.edit', compact('page_heading', 'cancellationPolicy'));

    }
 
    public function cancellation_store (Request $request) {

        $validator = Validator::make($request->all(), [
            'c_policy' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation failed", $validator->errors());
        }


        Setting::updateOrCreate(
            ['meta_key' => 'cms_cancellation_policy'], // Condition to find the record
            ['meta_value' => $request->c_policy] // Data to update or create
        );

        return response()->success("Saved successfully");
    }


    public function apiGetSettings()
    {
        $settings = $this->getAllSettings();
        return response()->success("Success", $settings);
    }

    public function apiGetPage(Request $request)
    {

        // Validate page type
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:contact_us,faq,about_us,terms_condition,privacy_policy',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation failed", $validator->errors());
        }

        $pageId = null;

        switch ($request->type) {
            case 'contact_us':
                $pageId = 5;
                break;
            case 'faq':
                $pageId = 2;
                break;
            case 'about_us':
                $pageId = 1;
                break;
            case 'terms_condition':
                $pageId = 4;
                break;

            case 'privacy_policy':
                $pageId = 3;
                break;
            default:
                # code...
                break;
        }


        $pages = Article::find($pageId);

        return response()->success("Success", $pages);
    }
}
