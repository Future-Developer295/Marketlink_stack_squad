@extends('Dashboard._master')

@section('nav_activity_log')
    active
@endsection

@section('page_title', 'Activity Log')

@section('body')

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-clock-history"></i>
                Activity / Audit Log
            </span>

            <div class="panel-tools">

                <form class="search-box" method="GET" action="{{ route('activity_log') }}">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search description or admin...">

                    @if (request('q'))
                        <a href="{{ route('activity_log') }}" class="search-clear" title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>

            </div>

        </div>

        <form method="GET" action="{{ route('activity_log') }}"
            style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; padding:14px 22px; border-bottom:1px solid var(--border);">

            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif

            <div>
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:4px;">Action</label>
                <select name="action" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All actions</option>
                    @foreach($actions as $actionOption)
                        <option value="{{ $actionOption }}" {{ request('action') === $actionOption ? 'selected' : '' }}>
                            {{ ucfirst($actionOption) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:4px;">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
            </div>

            <div>
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:4px;">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
            </div>

            <button type="submit" class="btn-primary" style="height:34px;">
                <i class="bi bi-funnel"></i> Filter
            </button>

            @if(request()->anyFilled(['action', 'from', 'to', 'q']))
                <a href="{{ route('activity_log') }}" class="btn-ghost" style="height:34px;">Clear</a>
            @endif

        </form>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>When</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)

                        <tr>

                            <td>
                                <span class="id-chip">{{ $log->id }}</span>
                            </td>

                            <td>
                                {{ $log->created_at->format('Y-m-d h:i A') }}
                            </td>

                            <td>
                                <strong>{{ $log->actor_name ?? 'N/A' }}</strong>
                                <br>
                                <span style="color:var(--muted); font-size:12px;">
                                    {{ ucfirst($log->actor_role ?? '') }}
                                </span>
                            </td>

                            <td>
                                @php
                                    $badgeClass = match ($log->action) {
                                        'approved' => 'bg-success',
                                        'deleted' => 'bg-danger',
                                        'rejected' => 'bg-danger',
                                        'flagged' => 'bg-warning',
                                        'unflagged' => 'bg-secondary',
                                        'updated' => 'bg-info',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>

                            <td>
                                {{ $log->description }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px;">
                                <i class="bi bi-clock-history" style="font-size:35px;"></i>
                                <br>
                                <strong>No activity recorded yet.</strong>
                                <br>
                                <span style="color:var(--muted);">
                                    Admin approvals, rejections and deletions will show up here.
                                </span>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="hr-thin"></div>

        <div style="padding:14px 22px;">
            {{ $logs->links() }}
        </div>

    </div>

@endsection
