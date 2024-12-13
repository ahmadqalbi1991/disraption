<?php

namespace App\Http\Controllers\vendor;

use App\Models\VendorModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categories;
use App\Models\User;
use App\Models\Vendor\VendorBooking;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder\Use_;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller
{
    private function getFirstLastDate($date){
        $datefrom = date("Y-m", strtotime($date))."-01";
        $dateto = date("Y-m-t", strtotime($date));
        
        return [$datefrom, $dateto];
    }
            
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        if (!get_user_permission('dashboard', 'r')) {
            return redirect()->route('vendor.restricted_page');
        }


        $type = "vendor";

        // Get total artist count
        $total_artists = null;

        // Get active artist count
        $active_artists = null;

        // Get total customer count
        $total_customers = null;

        // Get total category count
        $total_categories = null;


        // Get total bookings
        $total_bookings = VendorBooking::count();


        // Get today's bookings using created_at
        $today_bookings = VendorBooking::whereDate('created_at', date('Y-m-d'))->count();
        

        // Total cms pages
        $total_cms_pages = null;


        // Get current user id
        $user_id = Auth::id();

        $query = VendorBooking::with(['user'])->orderBy("id", "desc");

        if ($user_id !== "all") {
            $query->where('user_id', $user_id);
        }

        $bookings = $query->get();


        $page_heading = "Dashboard";
        
        return view('admin.dashboard', compact('page_heading', 'type', 'total_artists', 'active_artists', 'total_customers', 'total_categories', 'total_bookings', 'total_cms_pages', 'today_bookings', 'bookings'));
    }
    function getLastNDays($days, $format = 'd/m')
    {
        $m = gmdate("m");
        $de = gmdate("d");
        $y = gmdate("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] =  gmdate($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }
}
