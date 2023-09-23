@extends('layouts.app')

<title>Xhavo bookings</title>
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
                <h5 class="card-title fw-semibold">Merchant/Service Providers</h5>

              </div>
              <div class="table-responsive">
                <table id="xhavoTable" class="table border-striped align-middle">

                    <thead class="text-dark fs-4">
                        <tr style="margin-top: 20px">
                        <th>
                            <h6 class="fw-semibold mb-0">S/N</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Customer</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Merchant</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Booking Date/Time</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Booking Status</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Method of Identity</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">Identity Number</h6>
                        </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $id = 1;
                        @endphp
                        @foreach ($bookings as $booking)
                            <tr>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $id++ }}</h6></td>

                            <td class="border-bottom-0">
                                <h6 class="fw-semibold mb-1">{{ $booking->user->email }}</h6>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $booking->merchant->email }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $booking->booking_date->format('d M Y') }}, Time: {{ $booking->booking_time }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $booking->booking_status }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $booking->method_of_identity }}</p>
                            </td>
                            <td class="border-bottom-0">
                                <p class="mb-0 fw-semibold">{{ $booking->identity_number }}</p>
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


