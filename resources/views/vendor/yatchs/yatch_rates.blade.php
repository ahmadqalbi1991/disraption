@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout') @section('header')

<style>
    .gap-2 {
        gap: 10px;
    }

    .action-btns {
        gap: 15px;
        display: flex;
        align-items: center;
        justify-content: end;
    }

    .action-btns a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff !important;
        border-radius: 5px;
        padding: 0 !important;
    }

    .delete-btn {
        background: #ff2525 !important;
    }

    .edit-btn {
        background: #1bd1ea !important;
    }

    .time-slot-rates img {
        height: 30px;
        margin-bottom: 8px;
    }

    .time-slot-rates ul {
        display: -webkit-box;
        align-items: center;
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
    }

    .time-slot-rates ul li {
        width: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        background: #1d3466;
        color: #fff;
        list-style-type: none;
    }

    .time-slot-rates ul li:last-child {
        background: #d9faff;
        color: #1d3466;
        font-size: 13px;
    }

    .time-slot-rates h5 {
        color: #1d3466;
        font-weight: 600;
        font-size: 18px;
    }

    .toggle input[type="checkbox"] {
        height: 0;
        width: 0;
        visibility: hidden;
    }

    .toggle label {
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
        margin-bottom: 0;
        overflow: hidden;
    }

    .toggle label:after {
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

    .toggle input:checked+label {
        background: #ffffff;
        border-color: #1bd1ea;
    }

    .toggle input:checked+label:after {
        left: calc(100% - 5px);
        transform: translateX(-100%);
        background: #1bd1ea;
    }

    hr {
        margin: 0 0 15px;
        border-color: #e3f7fe;
    }

    .modal-dialog {
        width: 100%;
        max-width: 650px;
    }
</style>


@stop @section('content')

<div class="card mb-5">
    <div class="card-header">
        <a href="#!" class="btn-custom btn mr-2 mt-2 mb-2 add_special_rates"><i class="fa-solid fa-plus"></i> Add Special Rates</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6 col-lg-6 mb-4">
                @include('vendor.yatchs.parts.yacht_rate_list_time_row', ['db'=> ['type'=> 't_day', 'data'=> null, 'id'=> isset($yatchTodayRates) ? $yatchTodayRates['id'] : null], 'data' => $yatchTodayRates, 'title'=> "Today", 'date' => date('Y-m-d'), 'deleteBtn' => true])
            </div>

            <div class="col-12 mb-3">
                <div class="d-flex justify-content-between gap-2 align-items-center" style="max-width:500px">
                    <h6 class="mb-0">Override Special Day & Normal Day</h6>
                    <php print_r($overrideSd_Nd); ?>
                        <div class="toggle d-flex"><input class="override" data-type="ovr_sd_nd" type="checkbox" id="1" <?php echo ((int)$overrideSd_Nd == 1) ? 'checked' : ''; ?> /><label for="1">1</label></div>
                </div>
            </div>

            <div class="col-12 d-block d-lg-none">
                <hr />
            </div>


            <div class="col-12 mb-4">
                <hr />
            </div>


            <div class="col-12 mb-3">
                <h4>Special Rates</h4>
            </div>

            <div class="col-xl-6 col-lg-6 mb-4">

                @if ($yatchSpecialDayRates)

                @foreach ($yatchSpecialDayRates as $key => $data)
                @include('vendor.yatchs.parts.yacht_rate_list_time_row', ['db'=> ['type'=> 's_day', 'id'=> $data['id'], 'data'=> null], 'data' => $data, 'type'=> '', 'title'=> '', 'date'=> $data['date'], 'deleteBtn' => true, 'class'=> 'mb-4'])
                @endforeach

                @else

                <h6 class="mb-0">No special days added yet!</h6>

                @endif

            </div>

            <div class="col-12 mb-3">
                <div class="d-flex justify-content-between gap-2 align-items-center" style="max-width:500px">
                    <h6 class="mb-0">Override Normal Day</h6>
                    <div class="toggle d-flex"><input class="override" data-type="ovr_nd" type="checkbox" id="2" <?php echo ((int)$overrideNd == 1) ? 'checked' : ''; ?> /><label for="2">2</label></div>
                </div>
            </div>


            <div class="col-12 mb-4">
                <hr />
            </div>


            <div class="col-12 mb-3">
                <h4>Normal Rates</h4>
            </div>


            @foreach (['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'] as $key => $title)
            <div class="col-xl-6 col-lg-6 mb-4">
                @include('vendor.yatchs.parts.yacht_rate_list_time_row', ['db'=> ['type'=> 'n_day', 'data'=> $key, 'id'=> null], 'data' => isset($yatchNormalRates[$key]) ? $yatchNormalRates[$key] : null, 'type'=> $title, 'deleteBtn' => false])
            </div>
            @endforeach



        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ratesmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="admin-form">
        <input class="no_remove" type="hidden" name="yacht_id" value="{{$id}}" />
        <input class="no_remove" type="hidden" name="user_id" value="{{$user_id}}" />
        <input class="no_remove" type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="hidden" name="type" value="s_day" />
        <input type="hidden" name="id" value="" />
        <input type="hidden" name="data" value="" />

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Rates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12 mb-3 date_elem">
                            <div class="form-group">
                                <label>Select Date<b class="text-danger">*</b></label>
                                <input type="text" id="date" name="date" class="form-control flatpicker-future" required value="">
                            </div>
                        </div>
                        <div class="col-12 mb-3 text-center">
                            <h5 class="mb-0 display_date"><b>Today</b> - 27 Mar 2024 (Wednesday)</h5>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-2"><img src="{{ asset('') }}admin-assets/assets/img/morning.svg" class="img-fluid" height="23" /> <span style="font-size: 20px">Morning</span></h6>
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Start Time<b class="text-danger">*</b></label>
                                        <input id="mon_s" type="text" name="mon_s" class="form-control flatpicker-time" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>End Time<b class="text-danger">*</b></label>
                                        <input id="mon_e" type="text" name="mon_e" class="form-control flatpicker-time" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Rate<b class="text-danger">*</b></label>
                                        <input id="mon_r" type="text" name="mon_r" class="form-control frmt_price" required value="">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <h6 class="mb-2"><img src="{{ asset('') }}admin-assets/assets/img/afternoon.svg" class="img-fluid" height="23" /> <span style="font-size: 20px">Afternoon</span></h6>
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Start Time<b class="text-danger">*</b></label>
                                        <input id="aft_s" type="text" name="aft_s" class="form-control flatpicker-time" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>End Time<b class="text-danger">*</b></label>
                                        <input id="aft_e" type="text" name="aft_e" class="form-control flatpicker-time" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Rate<b class="text-danger">*</b></label>
                                        <input id="aft_r" type="text" name="aft_r" class="form-control frmt_price" required value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-2"><img src="{{ asset('') }}admin-assets/assets/img/evening.svg" class="img-fluid" height="23" /> <span style="font-size: 20px">Evening</span></h6>
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>Start Time<b class="text-danger">*</b></label>
                                        <input id="eve_s" type="text" name="eve_s" class="form-control flatpicker-time" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>End Time<b class="text-danger">*</b></label>
                                        <input id="eve_e" type="text" name="eve_e" class="form-control flatpicker-time" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>Rate<b class="text-danger">*</b></label>
                                        <input id="eve_r" type="text" name="eve_r" class="form-control frmt_price" required value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary" id="updateBtn">Update</button>
                </div>

            </div>
        </div>
    </form>
</div>

@stop @section('script')

<script>
    $(".flatpicker-future").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
    });

    $(".flatpicker-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
</script>


<script>
    App.initFormView();


    function clearFormFields() {
        // clear form fields except .no_remove fields
        $('#admin-form').find('input').not('.no_remove').val('');
    }

    /**
     * Updates the value of an input field with a formatted time.
     *
     * @param string $input The input field to update.
     * @param string $formatted_time The formatted time i.e 14:00 to set as the input value.
     * @return void
     */
    function updateInputTime($input, $formatted_time) {

        // Destroy the current Flatpickr instance
        $input.flatpickr().destroy();

        // Reinitialize the Flatpickr instance
        $input.flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: $formatted_time // Supply the time value as the defaultDate
        });

    }


    function compareAndAlertStartEndTime($startTimeElem, $endTimeElem, $alertPrefix = "", $alertMessage = " start time should be less than the end time!") {

        let $StartTime = $startTimeElem.val();
        let $EndTime = $endTimeElem.val();

        // The above vals are in this format i.e 22:00 etc so convert them to 24 hour time and compare
        let startTime = parseInt($StartTime.split(':')[0]);
        let endTime = parseInt($EndTime.split(':')[0]);



        if (startTime >= endTime) {
            App.alert($alertPrefix + $alertMessage, 'Oops!');
            return false;
        }


        return true

    }



    // On add_special_rates button click
    $('body').off('click', '.add_special_rates');
    $('body').on('click', '.add_special_rates', function(e) {
        e.preventDefault();

        // Update the modal title to Add Rate and update the button text to Add
        $('#ratesmodal').find('.modal-title').text('Add Special Rate');
        $('#ratesmodal').find('#updateBtn').text('Add');

        // Clear the form fields
        clearFormFields();

        // Set the form hidden input fields
        $('#ratesmodal').find('input[name="type"]').val('s_day');
        $('#ratesmodal').find('input[name="id"]').val('');
        $('#ratesmodal').find('input[name="data"]').val('');


        $('#ratesmodal').find('.date_elem').show();
        $('#ratesmodal').find('.date_elem input').attr('disabled', false).attr('required', true);

        // Update the display_date text
        $('#ratesmodal').find('.display_date').hide();

        // Show the modal
        $('#ratesmodal').modal('show');

    });

    // On update button click
    $('body').off('submit', '#admin-form');
    $('body').on('submit', '#admin-form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);


        // -------- Validate required fields ----------

        // if '#mon_s is empty
        if ($('#mon_s').val() == '') return App.alert('Morning start time is required!', 'Oops!');
        // if '#mon_e is empty
        if ($('#mon_e').val() == '') return App.alert('Morning end time is required!', 'Oops!');
        // if '#mon_r is empty
        if ($('#mon_r').val() == '') return App.alert('Morning rate is required!', 'Oops!');
        // if '#aft_s is empty
        if ($('#aft_s').val() == '') return App.alert('Afternoon start time is required!', 'Oops!');
        // if '#aft_e is empty
        if ($('#aft_e').val() == '') return App.alert('Afternoon end time is required!', 'Oops!');
        // if '#aft_r is empty
        if ($('#aft_r').val() == '') return App.alert('Afternoon rate is required!', 'Oops!');
        // if '#eve_s is empty
        if ($('#eve_s').val() == '') return App.alert('Evening start time is required!', 'Oops!');
        // if '#eve_e is empty
        if ($('#eve_e').val() == '') return App.alert('Evening end time is required!', 'Oops!');
        // if '#eve_r is empty
        if ($('#eve_r').val() == '') return App.alert('Evening rate is required!', 'Oops!');


        // If the date field is not disabled then check if it's empty
        if (!$('#ratesmodal').find('#date').is(':disabled')) {
            if ($('#ratesmodal').find('#date').val() == '') return App.alert('Date is required!', 'Oops!');
        }


        // ----------------------------------------------



        // ----------------- Validate time ----------------

        // If morning start time is greater than the end time then alert
        if (!compareAndAlertStartEndTime($('#mon_s'), $('#mon_e'), "Morning", )) return;

        // If afternoon start time is greater than the end time then alert
        if (!compareAndAlertStartEndTime($('#aft_s'), $('#aft_e'), "Afternoon", )) return;

        // If evening start time is greater than the end time then alert
        if (!compareAndAlertStartEndTime($('#eve_s'), $('#eve_e'), "Evening", )) return;

        // if morning end time is grater than the start time of afternoon then alert
        if (!compareAndAlertStartEndTime($('#mon_e'), $('#aft_s'), "", "Morning end time should be less than the start time of afternoon!")) return;

        // if afternoon end time is grater than the start time of evening then alert
        if (!compareAndAlertStartEndTime($('#aft_e'), $('#eve_s'), "", "Afternoon end time should be less than the start time of evening!")) return;

        // ----------------------------------------------------


        // Format the 
        formData.append('mon_s', timeTo24Format($('#mon_s').val()));
        formData.append('mon_e', timeTo24Format($('#mon_e').val()));
        formData.append('mon_r', $('#mon_r').val());
        formData.append('aft_s', timeTo24Format($('#aft_s').val()));
        formData.append('aft_e', timeTo24Format($('#aft_e').val()));
        formData.append('aft_r', $('#aft_r').val());
        formData.append('eve_s', timeTo24Format($('#eve_s').val()));
        formData.append('eve_e', timeTo24Format($('#eve_e').val()));
        formData.append('eve_r', $('#eve_r').val());


        // Submit the data to the server
        updateToServer(formData);


    });


    // On edit_btn click
    $('body').off('click', '.edit-btn');
    $('body').on('click', '.edit-btn', function(e) {
        e.preventDefault();


        let $this = $(this);
        let $parent = $this.closest('.action-btns');
        let data = $parent.data('data');
        let type = $parent.data('type');
        let id = $parent.data('id');
        let dbData = $parent.data('dbdata');
        try {
            dbData = JSON.parse(dbData);
        } catch (error) {}


        // Clear the form fields
        clearFormFields();


        // Set the form hidden input fields
        $('#ratesmodal').find('input[name="type"]').val(type);
        $('#ratesmodal').find('input[name="id"]').val(id);
        $('#ratesmodal').find('input[name="data"]').val(data);


        // If the type is the normal day then disable, remove required attr and  hide the .date_elem and inside it's input
        if (type == 'n_day' || type == 't_day') {
            $('#ratesmodal').find('.date_elem').hide();
            $('#ratesmodal').find('.date_elem input').attr('disabled', true).removeAttr('required');

            let displayDateTitle = type == 'n_day' ? data : 'Today';

            // if it's n_day then convert the 3 char day i.e mon to the Monday etc, without momemnt, do it hard coded way, make object
            let days = {
                'mon': 'Monday',
                'tue': 'Tuesday',
                'wed': 'Wednesday',
                'thu': 'Thursday',
                'fri': 'Friday',
                'sat': 'Saturday',
                'sun': 'Sunday',
            }

            // If it's n_day then get the full nname
            if (type == 'n_day') {
                displayDateTitle = days[data];
            }


            // Update the display_date text
            $('#ratesmodal').find('.display_date').html(`<b>${displayDateTitle}</b>`).show();

        } else {
            $('#ratesmodal').find('.date_elem').show();
            $('#ratesmodal').find('.date_elem input').attr('disabled', false).attr('required', true);

            // Hide the date text
            $('#ratesmodal').find('.display_date').hide();
        }


        // If date is set then update the date field
        if (dbData['date']) {
            $('#ratesmodal').find('#date').val(dbData['date']);
        }


        // Ready the time keys which will map with db columns and the html form fields
        ["mon_s", "mon_e", "mon_r", "aft_s", "aft_e", "aft_r", "eve_s", "eve_e", "eve_r"].forEach(function(item) {


            // Get the last character
            let lastChar = item[item.length - 1];

            // Get the input element
            let $input = $('#ratesmodal').find('input[name="' + item + '"]');


            // if it's not r which means it's 24 hour time value i.e 1, 4, 21 etc so convert it to "H:i" and set the .flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", }) element $input
            if (lastChar != 'r') {

                // if have time value then init and set the time
                if (dbData[item]) {

                    // Format the 24 hour number to proper time format
                    $formatted_time = format24ToTime(dbData[item])

                    // Update the input field with the formatted time
                    updateInputTime($input, $formatted_time);

                }




            } else {

                // It is the rate field

                // Set the rate value
                $input.val(dbData[item]);
            }


            // It's edit mode so update the modal title to Edit Rate and update the button text to update
            $('#ratesmodal').find('.modal-title').text('Edit Rate');
            $('#ratesmodal').find('#updateBtn').text('Update');

            // Show the modal
            $('#ratesmodal').modal('show');


        });






    });


    // On checkbox change
    $('body').off('change', '.override');
    $('body').on('change', '.override', function(e) {
        e.preventDefault();

        let $this = $(this);
        let type = $this.data('type');
        let val = $this.is(':checked') ? 1 : 0;

        // Ready the form data
        const formData = new FormData();
        // Aadd the yatch id, user id, _token and the type and value
        formData.append('yacht_id', "{{$id}}");
        formData.append('user_id', "{{$user_id}}");
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('type', type);
        formData.append('data', val);

        // Update the server
        updateToServer(formData, false);

    });


    // On delete button click
    $('body').off('click', '.delete-btn');
    $('body').on('click', '.delete-btn', function(e) {
        e.preventDefault();

        let $this = $(this);
        let $parent = $this.closest('.action-btns');
        let type = $parent.data('type');
        let id = $parent.data('id');

        // Confirm delete
        App.confirm('Confirm Delete', 'Are you sure that you want to delete this record?', function() {

            // Ready the form data
            const formData = new FormData();
            // Aadd the yatch id, user id, _token and the type and value
            formData.append('yacht_id', "{{$id}}");
            formData.append('user_id', "{{$user_id}}");
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('type', type);
            formData.append('id', id);

            // Update the server
            updateToServer(formData, true, "{{ route(route_name_admin_vendor($type, 'yacht_rate.delete'), ['user_id' => $user_id, 'type' => $type]) }}");

        });

    });


    function format24ToTime(hour) {
        // Ensure hour is within 0-23 range
        hour = Math.min(23, Math.max(0, parseInt(hour)));

        // Convert hour to string and pad with leading zero if necessary
        var formattedHour = ('0' + hour).slice(-2);

        // Return formatted time with minutes set to '00'
        return formattedHour + ':00';
    }


    function timeTo24Format(timeString) {
        // Split the time string into hours and minutes
        var parts = timeString.split(':');

        // Extract hours and minutes
        var hours = parseInt(parts[0], 10);

        // Ensure hours and minutes are within valid ranges
        hours = Math.min(24, Math.max(0, hours));

        // Combine hours and minutes to get the 24-hour format
        return hours;
    }

    // Function to update on the server
    function updateToServer(formData, reloadPage = true, submitUrl) {

        // if url is not provided
        submitUrl = submitUrl || "{{ route(route_name_admin_vendor($type, 'yacht_rate.save'), ['user_id' => $user_id, 'type' => $type]) }}";

        // Update button
        $updtButton = $("#updateBtn");

        // Button old text
        const oldText = $updtButton.text();

        const loading = () => {
            $updtButton.text('Submitting...');
            $updtButton.attr('disabled', true);
        }

        const done = () => {
            $updtButton.text(oldText);
            $updtButton.attr('disabled', false);
        }


        // Show loading
        loading();


        // Send the data to the server
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: submitUrl,
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

                    done();

                } else {

                    // Success
                    done();

                    // Reload the page
                    if (reloadPage) {

                        App.alert(res['message']);
                        setTimeout(function() {
                           
                            // Reload the page
                            location.reload();

                        }, 1500);

                    };

                }


            },
            error: function(e) {

                App.alert(e.responseText, 'Oops!');

                done();
            }
        });


    }
</script>


@stop