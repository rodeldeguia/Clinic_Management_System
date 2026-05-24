@extends('layouts.admin')

@section('title', 'Receptionist Activity Logs')
@section('header', 'Activity Logs')
@section('subheader', 'View receptionist activity history')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Action</th><th>Table Affected</th><th>Record ID</th><th>IP Address</th><th>Timestamp</th></tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->table_affected }}</td>
                    <td>{{ $log->record_id_affected }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->timestamp }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No activity logs found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</div>
@endsection