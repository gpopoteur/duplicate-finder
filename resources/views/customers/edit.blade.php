@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Edit Customer "{{ $customer->fullName }}"</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('customers.update', ['customer' => $customer->id]) }}" method="POST">
                {{ method_field('PATCH') }}
                @include('customers._form_fields', ['customer' => $customer, 'submitLabel' => 'Update'])
            </form>
        </div>
    </div>
@stop