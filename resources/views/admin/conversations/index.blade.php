@extends('layout.admin')

@section('content')
    <h1>قائمة المحادثات</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>العنوان</th>
                <th>تاريخ آخر رسالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conversations as $conversation)
                <tr>
                    <td>{{ $conversation->id }}</td>
                    <td>{{ $conversation->title }}</td>
                    <td>{{ $conversation->last_message_at }}</td>
                    <td>
                        <form action="{{ route('admin.conversations.destroy', $conversation->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('متأكد من الحذف؟')" class="btn btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
