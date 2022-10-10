@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card p-0">
                        <div class="row m-0">
                            <div class="col-3" style="margin: 0px; padding:0px; ">
                                <div class="card" style="margin: 0px; padding:0px; height: 100%;">
                                    <div class="card-body" style="margin: 0px;">
                                        <div class="balance-tabs">
                                            <button class="balanacelinks" onclick="switchTab(event, 'Overview')"
                                                id="defaultOpen">
                                                Overview
                                            </button>
                                            {{-- <button class="balanacelinks" onclick="switchTab(event, 'BankDeposit')"
                                                id="bankDeposittab">
                                                Bank Deposit
                                            </button> --}}
                                            <button class="balanacelinks" onclick="switchTab(event, 'Regulation')"
                                                id="regulationtab">
                                                Regulation
                                            </button>
                                            <button class="balanacelinks" onclick="switchTab(event, 'SaveOpertaion')"
                                                id="saveOpertaiontab">
                                                Save the Opertaion
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9" style="margin: 0px; padding:0px;">
                                <div class="card tabcontent" id="Overview" style="margin: 0px; height:100%;">
                                    @include('accounting.cash_management.overview')
                                </div>
                                <div class="card tabcontent" id="Regulation" style="margin: 0px; height:100%;">
                                    @include('accounting.cash_management.regulation')

                                </div>
                                <div class="card tabcontent" id="SaveOpertaion" style="margin: 0px; height:100%;">
                                    @include('accounting.cash_management.save_operation')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <input type="hidden" id="operation" value="{{ $operation }}">
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var operation = $('#operation').val();
            // alert(operation);
            if (operation == 1) {
                document.getElementById('Overview')
                    .style.display = "none";
                var tablinks = document
                    .getElementsByClassName(
                        "balanacelinks");
                for (i = 0; i < tablinks.length; i++) {
                    if (tablinks[i].id != "regulationtab") {
                        tablinks[i].className = tablinks[i]
                            .className.replace(" active",
                                "");
                    }
                }
                var tablink = document.getElementById(
                    "regulationtab");
                tablink.className = tablink.className +=
                    " active"
                document.getElementById('Regulation').style
                    .display = "block";
                    operation = 0;
            }
            $('#purchase-table').DataTable({
                // "processing": true,
                // "serverSide": true,
            });
            $('.primary-details').click(function() {
                console.log('here');
                window.location = '/';
            });
            $('.secoundry-details').click(function() {
                console.log('here');
                window.location = '/';
            });
        });
    </script>
    <script>
        function switchTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("balanacelinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
@endpush
