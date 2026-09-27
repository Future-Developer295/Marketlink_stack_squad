@extends('Dashboard._master')
@section('nav_farmers')
    active
@endsection
@section('page_title', 'Farmers')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-workspace"></i> Farmers</span>
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
                        <th>Market Name</th>
                        <th>Farmer Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($farmers as $farmer)
                        <tr>
                            <td>
                                <span class="id-chip">{{ $loop->iteration }}</span>
                            </td>

                            <td>
                                {{ $farmer->market?->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $farmer->farmer?->name ?? 'N/A' }}
                            </td>

                            <td>
                                @if ($farmer->is_active)
                                    <span class="badge-status bs-active">
                                        <i class="bi bi-circle-fill"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge-status bs-inactive">
                                        <i class="bi bi-circle-fill"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-wrap">
                                    <a href="{{ route('farmer_edit', $farmer->id) }}" class="btn-ghost sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">
            This table manages per-market farmer status. Use the panel below to approve or reject new farmer applications.
        </div>
    </div>

    <div class="panel" style="margin-top:20px;">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-hourglass-split"></i> Pending Farmer Applications</span>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Farmer Name</th>
                        <th>Email</th>
                        <th>Business Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingFarmerApplications as $application)
                        <tr>
                            <td><span class="id-chip">{{ $application->id }}</span></td>
                            <td>{{ $application->user->name ?? 'N/A' }}</td>
                            <td>{{ $application->user->email ?? 'N/A' }}</td>
                            <td>{{ $application->stall_name ?? $application->business_name ?? 'N/A' }}</td>
                            <td>
                                <div class="action-wrap">
                                    <form action="{{ route('farmer_approve', $application->id) }}" method="post">
                                        @csrf
                                        <button class="btn-ghost sm" type="submit" title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('farmer_reject', $application->id) }}" method="post">
                                        @csrf
                                        <button class="btn-ghost sm danger" type="submit" title="Reject">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--muted); padding:20px;">No pending applications.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
