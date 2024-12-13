@extends("admin.template.layout")

@section("content")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
<div class="card mb-5">
    <div class="card-body">
            <form method="post" id="admin-form" action="{{url('admin/change_password')}}" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="{{$userid}}">
                @csrf()
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>Current Password</label>
                            <div class="input-group mb-3">

                                <input id="password" type="password" class="form-control" name="cur_pswd" required autocomplete="current-password">

                                <div class="input-group-append" style="cursor: pointer; position: absolute; right: 20px; top: 16px; margin: 0; z-index: 9;">
                        <span class="input-group-text" onclick="password_show_hide();">
                          <i class="fas fa-eye d-none" id="show_eye"></i>
                          <i class="fas fa-eye-slash" id="hide_eye"></i>
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label>New Password</label>
                            <div class="input-group mb-3">

                                <input id="new_password" type="password" class="form-control" name="new_pswd" required autocomplete="current-password">

                                <div class="input-group-append" style="cursor: pointer; position: absolute; right: 20px; top: 16px; margin: 0; z-index: 9;">
                        <span class="input-group-text" onclick="new_password_show_hide();">
                          <i class="fas fa-eye d-none" id="show_eye_new"></i>
                          <i class="fas fa-eye-slash" id="hide_eye_new"></i>
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </div>
                </div>





            </form>
        <div class="col-xs-12 col-sm-6">

        </div>
    </div>
</div>
@stop

@section("script")
<script>
    function password_show_hide() {
        var x = document.getElementById("password");
        var show_eye = document.getElementById("show_eye");
        var hide_eye = document.getElementById("hide_eye");
        show_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            hide_eye.style.display = "none";
            show_eye.style.display = "block";
        } else {
            x.type = "password";
            hide_eye.style.display = "block";
            show_eye.style.display = "none";
        }
    }
</script>
<script>
    function new_password_show_hide() {
        var x = document.getElementById("new_password");
        var show_eye = document.getElementById("show_eye_new");
        var hide_eye = document.getElementById("hide_eye_new");
        show_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            hide_eye.style.display = "none";
            show_eye.style.display = "block";
        } else {
            x.type = "password";
            hide_eye.style.display = "block";
            show_eye.style.display = "none";
        }
    }
</script>
<script>
    var userId = <?php echo $userid;?>;
    console.log(userId);
        App.initFormView();
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();

            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined' && res['errors'] && res['errors'].length > 0) {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    if (error_index == 0) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function() {
                                var error = $form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            });
                        } else {
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        App.alert(res['message']);
                        setTimeout(function() {
                            if (userId) {
                                window.location.href = "{{ url('/admin/admin_users') }}"
                            } else {
                                window.location.href = "{{ url('/admin/') }}"
                            }
                           
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });


    </script>

@stop
