@extends('admin.dashboard')

@section('content')
    <h1>Manage Customers</h1>

    <div class="search-container">
        <form action="{{ route('customer.search') }}" method="GET">
            <input type="text" placeholder="Search.." name="query" class="search-input" value="{{ $query ?? '' }}">

            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <div class="card-body">
        <table id="customer" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>

                    <td>
                        @if (!$customer->is_approved)
                            <form action="{{ route('admin.customers.approve', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')


                                <button type="submit" class="button button-approve" onclick="confirmAction(event, 'Bạn chắc chắn muốn xác nhận khách hàng?')">Approve</button>
                            </form>
                        @else
                            <form action="{{ route('admin.customers.disapprove', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="button button-disapprove" onclick="confirmAction(event, 'Bạn có muốn hủy khách hàng?')">Disapprove</button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.customers.delete', $customer->id) }}" method="POST" style="display:inline;" onsubmit="confirmAction(event, 'Are you sure you want to delete this customer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
