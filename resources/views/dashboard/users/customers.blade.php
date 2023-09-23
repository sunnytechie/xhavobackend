@extends('layouts.app')

<title>Xhavo customers and clients</title>
<style>
    .thumbnail {
        width: 20px;
        height: 20px;
        object-fit: cover;
    }

    table tr td {
        border: 0.1rem solid #dee2e6;
    }

    thead tr th {
        background-color: #d4d6d8 !important;
        margin-top: 1rem !important;
    }
</style>

@section('content')
    <!--  Row -->
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
          <div class="card w-100">
            <div class="card-body p-4">
              <div class="d-flex justify-content-center align-items-center mb-3">
                <h5 class="card-title fw-semibold">Customers/Clients</h5>
              </div>
              <div class="table-responsive">
                <table id="xhavoTable" class="table border-striped align-middle">

                    <thead class="text-dark fs-4">
                        <tr style="margin-top: 20px">
                        <th>
                            <h6 class="fw-semibold mb-0">S/N</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Email</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Phone</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Action</h6>
                        </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $id = 1;
                        @endphp
                        @foreach ($customers as $customer)
                            <tr>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $id++ }}</h6></td>
                            <td class="border-bottom-0">
                                <h6 class="fw-semibold mb-1">{{ $customer->user->name }}</h6>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $customer->user->email }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $customer->user->phone }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <div class="d-flex">

                                <form class="m-0 p-0" method="POST" action="#">
                                        @csrf
                                    <button class="btn btn-danger rounded-0 btn-sm px-3" onclick="return confirm('Are you sure you want to block this user?')">Block User</button>
                                </form>

                                </div>
                            </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>

    </div>
@endsection

