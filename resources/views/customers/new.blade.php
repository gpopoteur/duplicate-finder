@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Create Customer</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('customers.store') }}" method="POST">
                @include('customers._form_fields', ['customer' => $customer, 'submitLabel' => 'Create'])
            </form>
        </div>
    </div>
@stop