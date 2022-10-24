@extends('layout.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
@section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-row-reverse">
                                <a class="btn btn-info" href="{{ route('chassis.index') }}">
                                    Back
                                </a>
                                <div class="col pt-1">
                                    <h4>{{ trans('file.Chassis Details') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('error'))
                                <p class="bg-danger text-white p-2 rounded">{{ Session::get('error') }}</p>
                            @endif
                            @if (Session::has('success'))
                                <p class="bg-success text-white p-2 rounded">{{ Session::get('success') }}</p>
                            @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session()->has('message'))
                                <div class="alert alert-success alert-dismissible text-center"><button type="button"
                                        class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>Chassis</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->CHASSIS) ? $chassis->CHASSIS : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>DAT_V</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->DAT_V) ? $chassis->DAT_V : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>DMC</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->DMC) ? $chassis->DMC : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>PUISSANCE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->PUISSANCE) ? $chassis->PUISSANCE : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>ENERGIE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->ENERGIE) ? $chassis->ENERGIE : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>CAR</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->CAR) ? $chassis->CAR : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>PTAC</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->PTAC) ? $chassis->PTAC : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>PTRA</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->PTRA) ? $chassis->PTRA : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>PVID</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->PVID) ? $chassis->PVID : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>PLA_AS</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->PLA_AS) ? $chassis->PLA_AS : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>CU</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->CU) ? $chassis->CU : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>CYL</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->CYL) ? $chassis->CYL : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>CD_TYP_CONS</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->CD_TYP_CONS) ? $chassis->CD_TYP_CONS : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>DAT_IMMAT</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->DAT_IMMAT) ? $chassis->DAT_IMMAT : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>GENRE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->GENRE) ? $chassis->GENRE : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>USAGE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->USAGE) ? $chassis->USAGE : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>TYP_COMM</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->TYP_COMM) ? $chassis->TYP_COMM : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>VILLE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->VILLE) ? $chassis->VILLE : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>MARQUE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->MARQUE) ? $chassis->MARQUE : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>PREMIERE_OPERATION</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->PREMIERE_OPERATION) ? $chassis->PREMIERE_OPERATION : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>GAUCHE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->GAUCHE) ? $chassis->GAUCHE : null }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>CD_SERIE</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->CD_SERIE) ? $chassis->CD_SERIE : null }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <strong>DROIT_MIL</strong>
                                                <input type="text" readonly class="form-control"
                                                    value="{{ isset($chassis->DROIT_MIL) ? $chassis->DROIT_MIL : null }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    </section>
@endsection
