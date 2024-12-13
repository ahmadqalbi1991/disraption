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
                <div class="toggle">
                    <input class="rate_field" type="checkbox" id="{{$day}}" <?php echo (isset($yachtRatesSchedule[$day]) && $yachtRatesSchedule[$day] == 1) ? 'checked' : ''; ?> />
                    <label for="{{$day}}">{{ strtoupper($day) }}</label>
                </div>
                <h5>{{ strtoupper($day) }}</h5>

                <div id="{{$day}}_wrap" class="time-slot mt-3">
                    @for($hour = 1; $hour <= 24; $hour++) <div class="time-slot-box">
                        <input class="cl-custom-check rate_field" id="{{$day}}_{{$hour}}" type="checkbox" <?php echo (isset($yachtRatesSchedule[$day . "_" . $hour]) && $yachtRatesSchedule[$day . "_" . $hour] == 1) ? 'checked' : ''; ?> />
                        <label class="cl-custom-check-label" for="{{$day}}_{{$hour}}">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</label>
                </div>
                @endfor
    </div>
    </li>
    @endforeach

    </ul>
</div>
</div>
@stop @section('script')

<script>
    App.initFormView();

    // on rate_field change get the checkbox value and push it to ratesData array by the id as key
    $('.rate_field').on('change', function() {

        let id = $(this).attr('id');
        let value = $(this).is(':checked') ? 1 : 0;

        let ratesData = [];

        ratesData[id] = value;

        let prevVal = value === 1 ? 0 : 1;


        // send the ratesData array to the server
        saveRatesData(ratesData, id, prevVal);


    });


    // Function to save the ratesData array to the server
    function saveRatesData(ratesData, id, prevVal) {
        // send the ratesData array to the server


        // Function to update the checkbox value
        function updateCheckboxOldDefault() {
            $('#' + id).prop('checked', prevVal == 1 ? true : false);
        }

        // raady the form data
        const formData = new FormData();

        // Loop through the ratesData array and append the data to the formData as ratesData is array
        for (let key in ratesData) {
            formData.append('rates_data[' + key + ']', ratesData[key]);
        }

        formData.append('yacht_id'," {{ $id }}");
        formData.append('user_id', "{{ $user_id }}");
        formData.append('_token', "{{ csrf_token() }}");

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route(route_name_admin_vendor($type, 'yacht_rate_schedule.save'), ['user_id' => $user_id, 'type' => $type]) }}",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: 'json',
            success: function(res) {


                if (res['status'] == 0) {

                    const m = res['message'] ||
                        'Unable to save! Please try again later.';
                    App.alert(m, 'Oops!');

                    // Update to default
                    updateCheckboxOldDefault();

                } else {
                    
                    // Success

                }

    
            },
            error: function(e) {
              
                App.alert(e.responseText, 'Oops!');

                // Update to default
                updateCheckboxOldDefault();
            }
        });

    }


</script>
@stop