@extends('layouts.app')

@section('content')
    <h3>Possible Duplicates for "{{ $customer->fullName }}"</h3>
    <hr>
    @unless($duplicates->count() > 0)
    <div class="well">No Duplicates found</div>
    @else
    <p>
        Hey {{ $customer->first_name }}, we feel like they this profiles could be duplicates, would like to merge the profiles?
    </p>
    <table class="table table-striped">
        <thead>
            <tr>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Email</td>
                <td>Gender</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach($duplicates as $customer)
            <tr>
                <td>{{ $customer->first_name }}</td>
                <td>{{ $customer->last_name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->gender }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endunless
@stop