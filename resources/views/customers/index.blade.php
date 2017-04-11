@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Customers <small class="pull-right"><a href="{{ route('customers.new') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create Customer</a></small></h3>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
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
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->first_name }}</td>
                        <td>{{ $customer->last_name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->gender }}</td>
                        <td>
                            <div class="pull-right">
                                <a href="{{ route('customers.duplicates', ['customer' => $customer->id]) }}" class="btn btn-default"><i class="fa fa-eye"></i> Find Duplicates</a>
                                <a href="{{ route('customers.edit', ['customer' => $customer->id]) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                <a href="#" class="btn btn-danger" data-delete data-customer="{{ $customer->id }}"><i class="fa fa-trash"></i> Delete </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {!! $customers->render() !!}

    <form id="delete-form" action="/" method="POST" style="display: none;">
        {{ method_field('DELETE') }}    
        {{ csrf_field() }}
    </form>
@stop

@section('scripts')
<script>
    function clickHandler(e) {
        if(confirm('Are you sure you want to delete this customer?')){
            var customerId = e.currentTarget.getAttribute("data-customer");
            var form = document.getElementById('delete-form');
            form.action = '/customers/' + customerId;
            form.submit();
        }
    }

    var anchors = document.querySelectorAll("a[data-delete]");
    
    for (var i = 0; i < anchors.length; i++) {
      anchors[i].addEventListener('click', clickHandler, false);
    }
</script>
@endsection