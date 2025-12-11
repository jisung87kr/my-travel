@php
    $isEdit = isset($vendor) && $vendor->exists;
@endphp

<!-- 담당자 정보 -->
<div class="px-6 py-5 border-b border-slate-200/60 bg-slate-50/50">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">담당자 정보</h3>
            <p class="text-sm text-slate-500">제공자 계정을 사용할 담당자 정보입니다.</p>
        </div>
    </div>
</div>
<div class="p-6 border-b border-slate-200/60">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                이름 <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $isEdit ? $vendor->user->name : '') }}"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('name') border-red-500 bg-red-50 @enderror"
                   placeholder="담당자 이름"
                   required>
            <x-form-error field="name" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                이메일 <span class="text-red-500">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email', $isEdit ? $vendor->user->email : '') }}"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('email') border-red-500 bg-red-50 @enderror"
                   placeholder="example@email.com"
                   required>
            <x-form-error field="email" />
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                전화번호
            </label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $isEdit ? $vendor->user->phone : '') }}"
                   placeholder="010-1234-5678"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('phone') border-red-500 bg-red-50 @enderror">
            <x-form-error field="phone" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                비밀번호 @if(!$isEdit)<span class="text-red-500">*</span>@endif
            </label>
            <input type="password" id="password" name="password"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('password') border-red-500 bg-red-50 @enderror"
                   placeholder="{{ $isEdit ? '변경 시에만 입력' : '최소 8자 이상' }}"
                   {{ $isEdit ? '' : 'required' }}>
            <p class="mt-2 text-xs text-slate-500">{{ $isEdit ? '비워두면 기존 비밀번호가 유지됩니다' : '최소 8자 이상' }}</p>
            <x-form-error field="password" />
        </div>
    </div>
</div>

<!-- 사업자 정보 -->
<div class="px-6 py-5 border-b border-slate-200/60 bg-slate-50/50">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">사업자 정보</h3>
            <p class="text-sm text-slate-500">제공자의 사업자 정보입니다.</p>
        </div>
    </div>
</div>
<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <label for="company_name" class="block text-sm font-medium text-slate-700 mb-2">
                회사명/상호 <span class="text-red-500">*</span>
            </label>
            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $isEdit ? ($vendor->business_name ?? $vendor->company_name) : '') }}"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('company_name') border-red-500 bg-red-50 @enderror"
                   placeholder="회사명 또는 상호명"
                   required>
            <x-form-error field="company_name" />
        </div>

        <div>
            <label for="business_number" class="block text-sm font-medium text-slate-700 mb-2">
                사업자등록번호
            </label>
            <input type="text" id="business_number" name="business_number" value="{{ old('business_number', $vendor->business_number ?? '') }}"
                   placeholder="123-45-67890"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('business_number') border-red-500 bg-red-50 @enderror">
            <x-form-error field="business_number" />
        </div>

        <div>
            <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-2">
                사업장 연락처
            </label>
            <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $vendor->contact_phone ?? '') }}"
                   placeholder="02-1234-5678"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('contact_phone') border-red-500 bg-red-50 @enderror">
            <x-form-error field="contact_phone" />
        </div>

        <div>
            <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-2">
                사업장 이메일
            </label>
            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $vendor->contact_email ?? '') }}"
                   placeholder="business@company.com"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('contact_email') border-red-500 bg-red-50 @enderror">
            <x-form-error field="contact_email" />
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                상태 <span class="text-red-500">*</span>
            </label>
            @php
                $currentStatus = old('status', $isEdit ? ($vendor->status->value ?? $vendor->status) : 'approved');
            @endphp
            <select id="status" name="status"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('status') border-red-500 bg-red-50 @enderror"
                    required>
                <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>승인 대기</option>
                <option value="approved" {{ $currentStatus === 'approved' ? 'selected' : '' }}>승인됨</option>
                <option value="rejected" {{ $currentStatus === 'rejected' ? 'selected' : '' }}>거절됨</option>
                @if($isEdit)
                    <option value="suspended" {{ $currentStatus === 'suspended' ? 'selected' : '' }}>정지됨</option>
                @endif
            </select>
            <x-form-error field="status" />
        </div>

        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                소개/설명
            </label>
            <textarea id="description" name="description" rows="3"
                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('description') border-red-500 bg-red-50 @enderror"
                      placeholder="사업장에 대한 간단한 소개">{{ old('description', $vendor->description ?? '') }}</textarea>
            <x-form-error field="description" />
        </div>
    </div>
</div>
