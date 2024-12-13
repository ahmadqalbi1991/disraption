@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')

@section('header')

<style>
    .features-list{
        display: flex;
        align-items: start;
        flex-wrap: wrap;
        gap: 15px
    }
    .features-list li{
        list-style-type: none;
        width: calc(25% - 15px);
    }
    
    @media(max-width:992px){
        .features-list li{
            list-style-type: none;
            width: calc(33.33% - 15px);
        }
    }
    @media(max-width:756px){
        .features-list li{
            list-style-type: none;
            width: calc(50% - 15px);
        }
    }
    
    @media(max-width:500px){
        
        .features-list li{
            list-style-type: none;
            width: 100%;
        }
    }
    .form-check-input {
        width: 25px;
        height: 25px;
        vertical-align: top;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid rgba(0, 0, 0, .25);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        overflow: hidden;
        border-radius: 5px;
    }
    .form-check .form-check-input {
        float: left;
    }
    .form-check-input:checked[type=checkbox] {
        background-image: url({{ asset('') }}admin-assets/assets/img/tick-icon.png);
        background-repeat: no-repeat;
        background-size: 13px;
    }
    .form-check-input:checked {
        background-color: #1BD1EA;
        border-color: #1BD1EA;
    }
    .form-check-label {
        margin-bottom: 0;
        margin-left: 15px;
        font-size: 20px;
    }
</style>
@stop




@section('content')
<div class="card mb-5">
    <div class="card-body">
        <ul class="features-list">
            <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="Fishing Equipment " checked="" wfd-id="id2">
                  <label class="form-check-label" for="Fishing Equipment ">
                    Fishing Equipment 
                  </label>
                </div>
            </li>
            <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="Googles "  wfd-id="id2">
                  <label class="form-check-label" for="Googles ">
                    Googles
                  </label>
                </div>
            </li>
            
            <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="Wifi " wfd-id="id2">
                  <label class="form-check-label" for="Wifi ">
                    Wifi
                  </label>
                </div>
            </li>
            <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="Diving Equipment " wfd-id="id2">
                  <label class="form-check-label" for="Diving Equipment ">
                    Diving Equipment
                  </label>
                </div>
            </li>
            <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="Sound System " wfd-id="id2">
                  <label class="form-check-label" for="Sound System ">
                    Sound System
                  </label>
                </div>
            </li>
           
            
            
        </ul>
    </div>
</div>

@stop

@section('script')

@stop