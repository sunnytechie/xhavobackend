@extends('layouts.guest')
@section('content')

<div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
<div class="d-flex align-items-center justify-content-center w-100">
  <div class="row justify-content-center w-100">
    <div class="col-md-8 col-lg-6 col-xxl-3">
      <div class="card mb-0">
        <div class="card-body">
          <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
            <img src="{{ asset('assets/images/logos/xhavo.png') }}" width="80" alt="">
          </a>
          <p class="text-center">administrator Password Reset</p>
          {{-- session status --}}
          <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="exampleInputEmail1" readonly aria-describedby="emailHelp" value="{{ old('email', $request->email) }}" placeholder="E-Mail Address">

                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong class="text-danger">{{ $message }}</strong>
                      </span>
                  @enderror
              </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="New Passcode">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password_confirmation" placeholder="Repeat Passcode">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Reset Password</button>

          </form>

        </div>
    </div>
  </div>
</div>
</div>
</div>

@endsection
