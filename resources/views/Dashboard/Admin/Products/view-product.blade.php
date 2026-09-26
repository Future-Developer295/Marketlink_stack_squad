@extends('Dashboard.Admin._master')
@section('nav_products')
active
@endsection
@section('page_title', 'Product Details')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-box-seam-fill"></i> Product Details</span>
            <a class="btn-ghost sm" href="{{ route('admin.products.index') }}"><i class="bi bi-x"></i> Back</a>
        </div>
        <div style="padding:22px">
            <div class="grid-2">
                <div class="view-img-area">
                    <img src="" alt="Dummy Product">
                </div>
                <div>
                    <div class="detail-grid">
                        <div class="detail-cell"><span class="dl">Name</span><span class="dv">Dummy Product</span></div>
                        <div class="detail-cell"><span class="dl">Farmer</span><span class="dv">Dummy Farmer</span></div>
                        <div class="detail-cell"><span class="dl">Category</span><span class="dv">Vegetables</span></div>
                        <div class="detail-cell"><span class="dl">Price</span><span class="dv">PKR 0.00</span></div>
                        <div class="detail-cell"><span class="dl">Stock</span><span class="dv">0 kg</span></div>
                        <div class="detail-cell"><span class="dl">Description</span><span class="dv">—</span></div>
                    </div>
                </div>
            </div>
            <div class="spacer-16"></div>
            <form action="{{ route('admin.products.destroy', 1) }}" method="post">
                @csrf
                <button class="btn-ghost sm danger" type="submit"><i class="bi bi-trash"></i> Remove Product</button>
            </form>
        </div>
    </div>
@endsection
