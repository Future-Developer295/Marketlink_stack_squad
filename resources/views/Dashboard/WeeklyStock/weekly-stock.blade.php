@extends('Dashboard._master')

@section('nav_stock_list')
active
@endsection

@section('page_title', 'Weekly Stock')

@section('body')

<div class="panel">

    <div class="panel-header">

        <span class="panel-title">
            <i class="bi bi-calendar2-week-fill"></i>
            Weekly Stock
        </span>

        <div class="panel-tools">

            <form class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Search…" />
            </form>

            <a class="btn-primary" href="{{ route('stock_add') }}">
                <i class="bi bi-plus-lg"></i>
                Add Schedule
            </a>

        </div>

    </div>


    <div class="tbl-wrap">

        <table class="dtable">

            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Product</th>
                    <th>Day</th>
                    <th>Quantity</th>
                    <th>Valid Period</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>


            <tbody>

            <?php

            $index = 1;
            ?>
                @foreach ($weekly_stock as $stock)

                <tr>

                    <td>
                        <span class="id-chip">
                            {{ $index++ }}
                        </span>
                    </td>


                    <td>
                        <strong>
                            {{ $stock->product->name }}
                        </strong>
                    </td>


                    <td>
                        <span class="badge-cat">
                            {{ $stock->day_of_week }}
                        </span>
                    </td>


                    <td>
                        <span class="qty-tag">
                            {{ $stock->quantity }} {{ $stock->unit }}
                        </span>
                    </td>


                    <td>

                        {{ $stock->start_date->format('d M') }}

                        @if ($stock->end_date)
                            – {{ $stock->end_date->format('d M') }}
                        @else
                            – No End Date
                        @endif

                    </td>


                    <td>

                        @if ($stock->is_active)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        @endif

                    </td>


                    <td>

                        <div class="action-wrap">

                            <a
                                class="btn-ghost sm"
                                href="{{ route('stock_edit', $stock->id) }}"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>


                            <form
                                action="{{ route('stock_delete', $stock->id) }}"
                                method="POST"
                            >

                                @csrf

                                <button
                                    class="btn-ghost sm danger"
                                    type="submit"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
