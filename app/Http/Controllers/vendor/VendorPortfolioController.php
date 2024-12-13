<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Vendor\VendorPortfolio;
use App\Models\Vendor\VendorUserDetail;

class VendorPortfolioController extends Controller
{



    public function create($type, $user_id)
    {
        if (!get_user_permission('vendors_portfolio','c')) {
            return redirect()->route('admin.restricted_page');
        }

         // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
         if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Portfolio";

        // Get the vendor portfolio by user_id using the portfolio model
        $vendor_portfolios = VendorPortfolio::where('user_id', $user_id)->orderBy('sort_order', 'asc')->get();

        // Replace the filename with the actual file path
        $vendor_portfolios->map(function ($portfolio) {
            $portfolio->filename = get_uploaded_image_url($portfolio->filename, 'portfolio');
            return $portfolio;
        });

        $page_heading = "Portfolio";

        return view('admin.vendors_portfolio.create', compact('page_heading', 'user_id', 'type', 'vendor_portfolios'));
    }





    // create the store function
    public function store(Request $request)
    {



        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        // Validate media variable in the request as array
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return $returnResponse('error', 'Validation error', $validator->errors());
        }


        // If the supplied user_id is not equal to the authenticated user's id, then check if the the current logged user is not adming then return error
        if ($request->user_id != Auth::user()->id && Auth::user()->user_type_id !== 1) {
            return $returnResponse(0, 'Unauthorized access');
        }



        // Get all portfolio media for the user_id
        $portfolioMedia = VendorPortfolio::where('user_id', $request->user_id)->get();

        // Format the portfolio media to have the id as the key
        $portfolioMedia = $portfolioMedia->keyBy('id')->toArray();


        // If the media is not in the request then set it to empty array
        if (!$request->has('media')) {
            $request->media = [];
        }



        // Loop through the media array in the request and if the id contain the new- then ready the file for upload and ready the array db insert so we will bulk insert the array
        $media = [];
        $updatedMedia = [];
        foreach ($request->media as $key => $value) {

            // --------------- Upload new links ----------------

            if (strpos($value['id'], 'new-') !== false) {

                // If file is not file object then skip this item
                if (!is_object($value['file'])) {
                    continue;
                }

                // Get media type image or video from the $value['file'] object
                $mediaType = explode('/', $value['file']->getMimeType())[0];

                // Get mime type of the file
                $mime = $value['file']->getMimeType();

                $fileName = "";

                // Upload the file
                $response =  single_image_upload($value['file'], 'portfolio', 'image');
                if ($response['status']) {
                    $fileName = $response['link'];
                }


                $media[] = [
                    'user_id' => $request->user_id,
                    'title' => $value['title'],
                    'description' => $value['description'],
                    'filename' => $fileName,
                    'mime' => $mime,
                    'type' => $mediaType,
                    'sort_order' => $value['sort_order'],
                ];
            }

            // --------------- End Upload new links ----------------

            // --------------- Check if the title, description or sort order changes for the already exist rows ----------------

            else {

                $dbMedia = $portfolioMedia[$value['id']];


                // if the title or description changes then set  to $updatedMedia so we will bulk update the array
                if ($dbMedia['title'] != $value['title'] || $dbMedia['description'] != $value['description'] || $dbMedia['sort_order'] != $value['sort_order']) {


                    $updatedMedia[] = [
                        'id' => $value['id'],
                        'title' => $value['title'],
                        'description' => $value['description'],
                        'sort_order' => $value['sort_order'],
                    ];
                }
            }

            // --------------------------------------------------------

        }

    

        // ---------- Loop through the db media and check if the id is not in the request media then ready the array of row ids to delete so we will bulk delete ------------
        $deleteMedia = [];
        foreach ($portfolioMedia as $media_i) {
            if (!in_array($media_i['id'], array_column($request->media, 'id'))) {
      
                $deleteMedia[] = $media_i['id'];
            }
        }

        // -----------------


        // Bulk insert the media array
        if (count($media) > 0) {
            VendorPortfolio::insert($media);
        }


        // ----- Bulk update the updatedMedia array in single query -----

        if (count($updatedMedia) > 0) {

            foreach ($updatedMedia as $media) {
                VendorPortfolio::where('id', $media['id'])->update([
                    'title' => $media['title'],
                    'description' => $media['description'],
                    'sort_order' => $media['sort_order'],
                ]);
            }
        }

        // -------------------------------------------------------------
        

        // Bulk delete the deleteMedia array
        if (count($deleteMedia) > 0) {
            VendorPortfolio::whereIn('id', $deleteMedia)->delete();
        }


        // All the media has been uploaded and updated successfully
        return $returnResponse('success', 'Portfolio updated successfully');
    }
}
