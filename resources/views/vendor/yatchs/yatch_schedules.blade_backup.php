@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout') @section('header')

<style>
    .yatch-schedule-list {
        display: flex;
        align-items: flex-start;
        flex-wrap: nowrap;
        gap: 10px;
    }

    .yatch-schedule-list li {
        list-style-type: none;
        text-align: center;
        width: calc(14.28% - 10px);
    }

    .yatch-schedule-list li input[type="checkbox"] {
        height: 0;
        width: 0;
        visibility: hidden;
    }

    .yatch-schedule-list li .toggle label {
        cursor: pointer;
        text-indent: -9999px;
        width: 56px;
        height: 32px;
        background: #ffffff;
        display: block;
        border-radius: 50rem;
        position: relative;
        border: 2px solid #e6e6e6;
        margin: auto;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .yatch-schedule-list li .toggle label:after {
        content: "";
        position: absolute;
        top: 2px;
        left: 3px;
        width: 24px;
        height: 24px;
        background: #b3b3b3;
        border-radius: 90px;
        transition: 0.3s;
    }

    .yatch-schedule-list li .toggle input:checked+label {
        background: #ffffff;
        border-color: #1bd1ea;
    }

    .yatch-schedule-list li .toggle input:checked+label:after {
        left: calc(100% - 5px);
        transform: translateX(-100%);
        background: #1bd1ea;
    }

    /*.yatch-schedule-list li .toggle label:active:after {*/
    /*	width: 130px;*/
    /*}*/

    .time-slot-box .cl-custom-check {
        display: none;
    }

    .time-slot-box .cl-custom-check+.cl-custom-check-label {
        /* Unchecked style  */
        background-color: #eee;
        color: #000;
        padding: 5px 10px;
        font-family: sans-serif;
        cursor: pointer;
        user-select: none;
        border-radius: 4px;
        display: inline-block;
        margin: 8px 0;
        backface-visibility: hidden;
        transition: all 0.6s ease;
    }

    .time-slot-box .cl-custom-check:checked+.cl-custom-check-label {
        /* Checked style  */
        background-color: #1d3466;
        backface-visibility: hidden;
        color: #fff !important;
    }

    .availibility-indicator {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 20px;
        justify-content: center;
        margin: 0 0 30px;
    }

    .availibility-indicator li {
        list-style-type: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        color: #252525;
    }

    .availibility-indicator li span {
        width: 20px;
        height: 20px;
        display: inline-block;
        border-radius: 2px;
        margin-right: 3px;
    }

    .available-color {
        background: #1D3466;
    }

    .not-available-color {
        background: #A8B5B6;
    }



    .time-slot-box .cl-custom-check:disabled+.cl-custom-check-label {
        background: #A8B5B6;
        color: #fff;
    }
</style>
@stop @section('content')

<?php
$days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
?>

<div class="card mb-5">
    <div class="card-body">
        <p class="text-center mb-4 text-muted">Important note: Tap on time slot it’ll make available.</p>

        <ul class="availibility-indicator">
            <li>
                <span class="available-color"></span> Available
            </li>

            <li>
                <span class="not-available-color"></span> Not Available
            </li>
        </ul>
        <ul class="yatch-schedule-list">

            @foreach($days as $day)
            <li>
                <div class="toggle"><input type="checkbox" id="{{$day}}" /><label for="{{$day}}">{{ strtoupper($day) }}</label></div>
                <h5>{{ strtoupper($day) }}</h5>

                <div id="{{$day}}_wrap" class="time-slot mt-3">
                    @for($hour = 1; $hour <= 24; $hour++)
                        <div class="time-slot-box">
                            <input class="cl-custom-check" id="{{$day}}_{{$hour}}" type="checkbox" />
                            <label class="cl-custom-check-label" for="{{$day}}_{{$hour}}">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</label>
                        </div>
                    @endfor
                </div>
            </li>
            @endforeach

            <li>
                <div class="toggle"><input type="checkbox" id="Sun" /><label for="Sun">Sun</label></div>
                <h5>Sun</h5>

                <div id="sunday" class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun1" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun2" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun3" type="checkbox" disabled />
                        <label class="cl-custom-check-label" for="sun3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun4" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun5" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun6" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun7" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun8" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun9" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun10" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun11" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun12" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun13" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun14" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun15" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun16" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun17" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun18" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun19" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun20" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun21" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun22" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun23" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sun24" type="checkbox" />
                        <label class="cl-custom-check-label" for="sun24">24:00</label>
                    </div>
                </div>
            </li>
            <li>
                <div class="toggle"><input type="checkbox" id="Mon" /><label for="Mon">Mon</label></div>
                <h5>Mon</h5>

                <div class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon1" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon2" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon3" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon4" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon5" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon6" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon7" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon8" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon9" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon10" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon11" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon12" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon13" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon14" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon15" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon16" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon17" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon18" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon19" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon20" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon21" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon22" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon23" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="mon24" type="checkbox" />
                        <label class="cl-custom-check-label" for="mon24">24:00</label>
                    </div>
                </div>
            </li>
            <li>
                <div class="toggle"><input type="checkbox" id="Tue" /><label for="Tue">Tue</label></div>
                <h5>Tue</h5>

                <div class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue1" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue2" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue3" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue4" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue5" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue6" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue7" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue8" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue9" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue10" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue11" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue12" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue13" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue14" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue15" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue16" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue17" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue18" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue19" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue20" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue21" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue22" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue23" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="tue24" type="checkbox" />
                        <label class="cl-custom-check-label" for="tue24">24:00</label>
                    </div>
                </div>
            </li>
            <li>
                <div class="toggle"><input type="checkbox" id="Wed" /><label for="Wed">Wed</label></div>
                <h5>Wed</h5>

                <div class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed1" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed2" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed3" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed4" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed5" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed6" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed7" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed8" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed9" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed10" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed11" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed12" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed13" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed14" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed15" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed16" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed17" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed18" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed19" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed20" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed21" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed22" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed23" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="wed24" type="checkbox" />
                        <label class="cl-custom-check-label" for="wed24">24:00</label>
                    </div>
                </div>
            </li>
            <li>
                <div class="toggle"><input type="checkbox" id="Thu" /><label for="Thu">Thu</label></div>
                <h5>Thu</h5>

                <div class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu1" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu2" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu3" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu4" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu5" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu6" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu7" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu8" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu9" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu10" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu11" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu12" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu13" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu14" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu15" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu16" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu17" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu18" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu19" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu20" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu21" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu22" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu23" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="thu24" type="checkbox" />
                        <label class="cl-custom-check-label" for="thu24">24:00</label>
                    </div>
                </div>
            </li>
            <li>
                <div class="toggle"><input type="checkbox" id="Fri" /><label for="Fri">Fri</label></div>
                <h5>Fri</h5>

                <div class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri1" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri2" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri3" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri4" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri5" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri6" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri7" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri8" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri9" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri10" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri11" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri12" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri13" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri14" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri15" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri16" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri17" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri18" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri19" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri20" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri21" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri22" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri23" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="fri24" type="checkbox" />
                        <label class="cl-custom-check-label" for="fri24">24:00</label>
                    </div>
                </div>
            </li>
            <li>
                <div class="toggle"><input type="checkbox" id="Sat" /><label for="Sat">Toggle</label></div>
                <h5>Sat</h5>

                <div class="time-slot mt-3">
                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat1" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat1">01:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat2" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat2">02:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat3" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat3">03:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat4" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat4">04:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat5" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat5">05:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat6" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat6">06:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat7" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat7">07:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat8" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat8">08:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat9" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat9">09:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat10" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat10">10:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat11" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat11">11:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat12" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat12">12:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat13" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat13">13:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat14" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat14">14:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat15" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat15">15:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat16" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat16">16:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat17" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat17">17:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat18" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat18">18:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat19" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat19">19:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat20" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat20">20:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat21" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat21">21:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat22" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat22">22:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat23" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat23">23:00</label>
                    </div>

                    <div class="time-slot-box">
                        <input class="cl-custom-check" id="sat24" type="checkbox" />
                        <label class="cl-custom-check-label" for="sat24">24:00</label>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
@stop @section('script')

<script></script>
@stop