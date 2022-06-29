@extends('layouts.app')

@section('content')
    <!-- Main content-->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="view-header">
                        <div class="header-icon">
                            <i class="pe page-header-icon pe-7s-albums"></i>
                        </div>
                        <div class="header-title">
                            <h3>Shopify Seller Products</h3>
                            <small>
                                Table for shopify seller products
                            </small>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-filled">
                        <div class="panel-body">
                            <table class="table table-responsive-sm" id="maintable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Product Type</th>
                                    <th>Tags</th>
                                    <th>Published Scope</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>SKU</th>
                                    <th>Vendor</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Currency</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End main content-->
    <script src="vendor/datatables/datatables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#maintable').DataTable({
                order: [[ 0, "asc" ]],
                scrollX:true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                lengthMenu: [[50, 100, 200, 300, 500], [50, 100, 200, 300, 500]],
                processing: true,
                serverSide: true,
                autoWidth: false,
                paging: true,
                searching: true,
                searchDelay: 1000,
                ordering: true,
                language: {
                    zeroRecords: "No data Found",
                    processing: 'Loading...'
                },
                // columnDefs: [
                //     { "width": "110px", "targets": [1,2] }
                // ],
                rowId: 'id',
                info: false,
                ajax: {
                    url: "{{ route('api.shopify.products') }}?api_token={{auth()->user()->api_token}}",
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id', searchable: false },
                    { data: 'title', searchable: false },
                    { data: 'product_type', searchable: false },
                    { data: 'tags', searchable: false, orderable: false },
                    { data: 'published_scope', searchable: false },
                    { data: 'image', searchable: false, orderable: false },
                    { data: 'status', searchable: false },
                    { data: 'sku', searchable: false },
                    { data: 'vendor', searchable: false },
                    { data: 'quantity', searchable: false },
                    { data: 'price', searchable: false },
                    { data: 'currency', searchable: false, orderable: false }
                ],
                buttons: [
                    {extend: 'copy', className: 'btn-sm'},
                    {extend: 'csv', title: 'CSV File', className: 'btn-sm'},
                    {extend: 'pdf', title: 'PDF File', className: 'btn-sm'},
                    {extend: 'print', className: 'btn-sm'}
                ]
            });
        });
    </script>
@endsection
