<x-guest-layout>
    <h2 class="mb-6 text-xl font-bold text-gray-900">Sign in to your account</h2>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                   value="{{ old('email') }}"
                   class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs text-teal-600 hover:text-teal-700 transition-colors">Forgot password?</a>
            </div>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" name="remember" type="checkbox"
                   class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500 accent-teal-600">
            <label for="remember_me" class="text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
            Sign in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-700 transition-colors">Sign up free</a>
    </p>
</x-guest-layout>
