@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout') 

@section('header')

@stop 


@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label>Offer title<b class="text-danger">*</b></label>
                        <input type="text" name="name" class="form-control" required="" value="" />
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label>Short Description<b class="text-danger">*</b></label>
                        <input type="text" name="name" class="form-control" required="" value="" />
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label>Offer Percentage(%)<b class="text-danger">*</b></label>
                        <input type="text" name="name" class="form-control" required="" value="" />
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label>Offer Price<b class="text-danger">*</b></label>
                        <input type="text" name="name" class="form-control" required="" value="" />
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <label>Offer Price<b class="text-danger">*</b></label>
                        <select class="form-control">
                            <option>Offer for Packages / Yacht booking</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label>Image</label><br />
                        <input type="file" name="image" class="form-control mb-2" />
                        <img id="image-preview" style="width: 100px; height: 90px;" class="img-responsive" />
                        <br />
                        <br />
                        <span class="text-info">Upload image with dimension 300x300</span>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@stop 



@section('script')

@stop