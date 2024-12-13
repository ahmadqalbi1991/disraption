@foreach($categories as $key => $val)
        <optgroup label="<?php echo $val->name; ?>">
                @foreach($val->sub as $sub)
                        <option data-style="background-color: #ff0000;" value="<?php echo $sub->id; ?>">
                                <?php echo str_repeat('&nbsp;', 4) . $sub->name; ?>
                        </option>
                @endforeach
        </optgroup>
@endforeach
