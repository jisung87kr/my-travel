<x-layouts.admin>
    <x-slot name="header">사용자 관리</x-slot>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="이름 또는 이메일 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">전체 역할</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>관리자</option>
                    <option value="vendor" {{ request('role') === 'vendor' ? 'selected' : '' }}>제공자</option>
                    <option value="traveler" {{ request('role') === 'traveler' ? 'selected' : '' }}>여행자</option>
                </select>
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">전체 상태</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>활성</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>비활성</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>차단</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    검색
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">사용자</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">역할</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">가입일</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">노쇼</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_blocked)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">차단</span>
                            @elseif($user->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">활성</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">비활성</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="{{ $user->no_show_count >= 3 ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                {{ $user->no_show_count }}회
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                상세
                            </a>
                            @if($user->is_blocked)
                                <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        차단해제
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                        {{ $user->is_active ? '비활성화' : '활성화' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            사용자가 없습니다.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.admin>
