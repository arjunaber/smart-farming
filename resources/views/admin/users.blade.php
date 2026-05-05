@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-black text-slate-800 dark:text-white">Super Admin - Manajemen User</h1>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border p-8 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left py-4">Nama</th>
                        <th class="text-left py-4">Email</th>
                        <th class="text-left py-4">Role</th>
                        <th class="text-left py-4">Jumlah Lahan</th>
                        <th class="py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-t">
                            <td class="py-4 font-bold">{{ $user->name }}</td>
                            <td class="py-4">{{ $user->email }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->role == 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-4">{{ $user->lahan_count }}</td>
                            <td class="py-4">
                                <a href="#" class="text-blue-600 hover:text-blue-700">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection

