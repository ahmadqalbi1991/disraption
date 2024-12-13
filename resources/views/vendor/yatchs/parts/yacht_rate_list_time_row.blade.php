<?php

use App\Http\Controllers\vendor\YacthRateController;

$noRatesSetMsg = "No rates set!";

// Get today date in 27 Mar 2024 format
$todayDate = date('d M Y');

// Get today day full name
$todayDayName = date('l');

?>

<div class="row <?php echo isset($class) ? $class : ""; ?>">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between gap-2 align-items-center">
            <h5 class="mb-0"><b>{{$title}}</b>
            @if (isset($date) && $date)
                -  {{date('d M Y', strtotime($date))}} ({{date('l', strtotime($date))}})
            @endif
            </h5>
            <div class="action-btns" data-type="{{$db['type']}}" data-data="{{$db['data']}}" data-id="{{$db['id']}}" data-dbdata="{{isset($data) ? json_encode($data) : null }}">
                <a href="#!" class="edit-btn"><i class="fa-regular fa-pen-to-square"></i></a>
                @if($deleteBtn)
                <a href="#!" class="delete-btn"><i class="fa-regular fa-trash-can"></i></a>
                @endif


            </div>
        </div>
    </div>
    <!--<div class="col-4 mb-4 text-end">-->

    <!--</div>-->
    <div class="col-4 mb-4 text-center">
        <div class="time-slot-rates">
            <img src="{{ asset('') }}admin-assets/assets/img/morning.svg" />
            <h6>Morning</h6>

            @if ($data)
            <ul class="timings">
                <li><?php echo YacthRateController::hour24Format($data['mon_s']); ?></li>
                <li><?php echo YacthRateController::hour24Format($data['mon_e']); ?></li>
            </ul>
            <h5 class="mb-0">{{$data['mon_r']}} AED/hr</h5>

            @else

            <h5 class="mb-0">{{$noRatesSetMsg}}</h5>

            @endif

        </div>
    </div>
    <div class="col-4 mb-4 text-center">
        <div class="time-slot-rates">
            <img src="{{ asset('') }}admin-assets/assets/img/afternoon.svg" />
            <h6>Afternoon</h6>

            @if ($data)
            <ul class="timings">
                <li><?php echo YacthRateController::hour24Format($data['aft_s']); ?></li>
                <li><?php echo YacthRateController::hour24Format($data['aft_e']); ?></li>
            </ul>
            <h5 class="mb-0">{{$data['aft_r']}} AED/hr</h5>

            @else

            <h5 class="mb-0">{{$noRatesSetMsg}}</h5>

            @endif

        </div>
    </div>
    <div class="col-4 mb-4 text-center">
        <div class="time-slot-rates">
            <img src="{{ asset('') }}admin-assets/assets/img/evening.svg" />
            <h6>Evening</h6>
            @if ($data)
            <ul class="timings">
                <li><?php echo YacthRateController::hour24Format($data['eve_s']); ?></li>
                <li><?php echo YacthRateController::hour24Format($data['eve_e']); ?></li>
            </ul>
            <h5 class="mb-0">{{$data['eve_r']}} AED/hr</h5>

            @else

            <h5 class="mb-0">{{$noRatesSetMsg}}</h5>

            @endif

        </div>
    </div>

    @if (isset($overRide) && $overRide)

    <div class="col-12">
        <div class="d-flex justify-content-between gap-2 align-items-center">
            <h6 class="mb-0">{{$overRide['text']}}</h6>
            <div class="toggle"><input class="override" data-type="{{$overRide['type']}}" type="checkbox" id="1" <?php echo ($overRide['val'] == 1) ? 'checked' : ''; ?> /><label for="1">1</label></div>
        </div>
    </div>

    @endif


</div>