@extends('Dashboard._master')
@section('body')
    <div class="stats-row">
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-num">5</div>
            <div class="stat-label">Total Products</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> +2 this week</div>
        </div>
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-bookmarks-fill"></i></div>
            <div class="stat-num">1</div>
            <div class="stat-label">Categories</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> All active</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-num">2</div>
                  <div class="stat-label">In Stock</div>

            <div class="stat-trend up">
                <i class="bi bi-arrow-up-right"></i>
               3% available
            </div>
          

        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-num"></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Out of Stock</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Needs restock</div>
             
        </div>
        <div class="stat-card sc-purple">
            <div class="stat-icon-wrap"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-num">
                PKR 500
            </div>
            <div class="stat-label">Inventory Value</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Est. total</div>
        </div>
    </div>


    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-clock-history"></i> Recent Products</span>
            <a class="btn-ghost" href="{{route('products')}}">View all <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
