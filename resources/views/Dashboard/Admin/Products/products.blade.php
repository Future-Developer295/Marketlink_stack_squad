@extends('Dashboard.Admin._master')
@section('nav_products')
active
@endsection
@section('page_title', 'Products')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-box-seam-fill"></i> Products</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Product</th>
                        <th>Farmer</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><div class='prod-cell'><div class='prod-img'><img src="" alt=""></div><div class='prod-info'><strong>Dummy Product</strong><span>Unit: kg</span></div></div></td>
                            <td>Dummy Farmer</td>
                            <td><span class='badge-cat'>Vegetables</span></td>
                            <td><span class='price-mono'>PKR 0.00</span></td>
                            <td><span class='qty-tag'>0</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("admin.products.show", 1) }}'><i class='bi bi-eye'></i></a><form action='{{ route("admin.products.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Admins can view all farmer listings and remove violating products, but cannot add or edit product details.</div>
    </div>
@endsection
