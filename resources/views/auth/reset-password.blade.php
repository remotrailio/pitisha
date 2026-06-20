<x-guest-layout>
    <h2 class="mb-6 text-xl font-bold text-gray-900">Set new password</h2>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                   value="{{ old('email', $request->email) }}"
                   class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New password</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required
                   class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                   class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150">
        </div>

        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
            Reset password
        </button>
    </form>
</x-guest-layout>
