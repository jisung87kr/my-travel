<x-layouts.app :title="__('nav.profile')">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('nav.profile') }}</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('my.profile.update', ['locale' => app()->getLocale()]) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('booking.contact_name') }}
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email (readonly) -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('booking.contact_email') }}
                    </label>
                    <input type="email"
                           id="email"
                           value="{{ $user->email }}"
                           readonly
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('booking.contact_phone') }}
                    </label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preferred Language -->
                <div class="mb-6">
                    <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-2">
                        Preferred Language
                    </label>
                    <select id="preferred_language"
                            name="preferred_language"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="ko" {{ $user->preferred_language->value === 'ko' ? 'selected' : '' }}>한국어</option>
                        <option value="en" {{ $user->preferred_language->value === 'en' ? 'selected' : '' }}>English</option>
                        <option value="zh" {{ $user->preferred_language->value === 'zh' ? 'selected' : '' }}>中文</option>
                        <option value="ja" {{ $user->preferred_language->value === 'ja' ? 'selected' : '' }}>日本語</option>
                    </select>
                </div>

                <!-- Account Status -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Account Status</h3>
                    <div class="text-sm text-gray-600">
                        <p>Member since: {{ $user->created_at->format('Y-m-d') }}</p>
                        @if($user->no_show_count > 0)
                            <p class="text-orange-600">No-show count: {{ $user->no_show_count }}/3</p>
                        @endif
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
