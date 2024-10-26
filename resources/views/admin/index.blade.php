@extends('layouts.layoutsadmin')

@section('content')
<div class="container">
    <h1>休暇・有給・欠勤申請の承認</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>申請種類</th>
                <th>日付</th>
                <th>理由</th>
                <th>状態</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveRequests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->type }}</td>
                    <td>{{ $request->date }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>{{ $request->status }}</td>
                    <td>
                        <form action="{{ route('admin.leave_requests.approve', $request->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">承認</button>
                        </form>
                        
                        <form action="{{ route('admin.leave_requests.reject', $request->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">差し戻し</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
