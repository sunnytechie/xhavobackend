@extends('layouts.guest')
@section('content')

<div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
<div class="d-flex align-items-center justify-content-center w-100">
  <div class="row justify-content-center w-100">
    <div class="col-md-4">
      <div class="card mb-0">
        <div class="card-body">
          <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
            <img src="{{ asset('assets/images/logos/xhavo.png') }}" width="80" alt="">
          </a>
          <p class="text-center">administrator login</p>
          {{-- session status --}}
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('email') }}" placeholder="E-Mail Address">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Passcode">
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="form-check">
                <input class="form-check-input primary" type="checkbox" name="remember" value="" id="flexCheckChecked" checked>
                <label class="form-check-label text-dark" for="flexCheckChecked">
                  Remeber this device
                </label>
              </div>
              @if (Route::has('password.request'))
              <a class="text-primary fw-bold" href="{{ route('password.request') }}">Forgot Password ?</a>
              @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
            <div class="d-flex align-items-center justify-content-center">
              <p class="fs-4 mb-0 fw-bold">New to xhavo.app?</p>
              <a class="text-primary fw-bold ms-2" href="#">Create an account</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@endsection
