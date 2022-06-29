@extends('layouts.app')

@section('content')
     <!-- Main content-->
     <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="view-header">
                        <div class="header-icon">
                            <i class="pe page-header-icon pe-7s-albums"></i>
                        </div>
                        <div class="header-title">
                            <h3>Dashboard</h3>
                            <small>
                                Overview
                            </small>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="panel panel-filled" id="card_all">
                        <div class="panel-body">
                            <div class="small">Number of Products</div>
                            <h2 class="m-b-none server1">{{$total['all']}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="panel panel-filled" id="card_available">
                        <div class="panel-body">
                            <div class="small">Number of Available Stocks</div>
                            <h2 class="m-b-none server2">{{$total['available']}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="panel panel-filled" id="card_new">
                        <div class="panel-body">
                            <div class="small">Number of New Stocks</div>
                            <h2 class="m-b-none server2">{{$total['new']}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="panel panel-filled" id="card_old">
                        <div class="panel-body">
                            <div class="small">Number of Old Stocks</div>
                            <h2 class="m-b-none server2">{{$total['old']}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- End main content-->
<script src="/vendor/datatables/datatables.min.js"></script>
<script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        var card_all = document.getElementById('card_all');
        card_all.style.cursor = 'pointer';
        card_all.onclick = function() {
            location.href = "/allproducts";
        };
        
        var card_available = document.getElementById('card_available');
        card_available.style.cursor = 'pointer';
        card_available.onclick = function() {
            location.href = "/availablestocks";
        };
        var card_new = document.getElementById('card_new');
        card_new.style.cursor = 'pointer';
        card_new.onclick = function() {
            location.href = "/newstocks";
        };
        /*
        var card_old = document.getElementById('card_old');
        card_old.style.cursor = 'pointer';
        card_old.onclick = function() {
            location.href = "/oldstocks";
        };
        */
    });
</script>
@endsection