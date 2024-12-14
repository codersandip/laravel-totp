@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <form action="">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="totp_enabled" @checked(auth()->user()->totp_enabled)>
                                        <label class="form-check-label" for="totp_enabled">Enabled Time Based One Time
                                            Password</label>
                                    </div>
                                    <div>
                                        <img src="" alt="" class="mx-auto d-block" id="totp_qr">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="module">
        $(document).ready(function() {
            $('#totp_enabled').change(function() {
                axios.post('{{ route('totp-enable') }}', {
                    totp_enabled: this.checked
                }).then(function(response) {
                    if (response.data.status) {
                        if (response.data.data.qr) {
                            console.log(response.data.data.qr);
                            QRCode.toDataURL(response.data.data.qr.toString(),
                                function(err, url) {
                                    console.log(url);
                                    
                                    $('#totp_qr').attr('src', url);
                                })
                        }
                    }
                });
            });
        });
    </script>
@endpush
