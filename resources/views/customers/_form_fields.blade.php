{!! csrf_field() !!}

<!-- First Name Field -->
<div class="form-group">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" class="form-control" value="{{ $customer->first_name or old('first_name') }}"/>
</div>

<!-- Last Name Field -->
<div class="form-group">
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" class="form-control" value="{{ $customer->last_name or old('last_name') }}"/>
</div>

<!-- Email Field -->
<div class="form-group">
    <label for="email">Email:</label>
    <input type="text" name="email" class="form-control" value="{{ $customer->email or old('email') }}"/>
</div>

<!-- Gender Field -->
<div class="form-group">
    <label for="gender">Gender:</label>
    <select name="gender" class="form-control">
        @foreach(App\Customer::$genderOptions as $gender)
        <option value="{{ $gender }}" @if($gender === $customer->gender) selected @endif>{{ $gender }}</option>
        @endforeach
    </select>
</div>

<!-- Last Ip Field -->
<div class="form-group">
    <label for="last_ip">Last Ip:</label>
    <input type="text" name="last_ip" class="form-control" value="{{ $customer->last_ip or old('last_ip') }}"/>
</div>

<div class="form-group">
    <button type="submit" name="submit" class='btn btn-primary'>{{ $submitLabel }}</button>
</div>