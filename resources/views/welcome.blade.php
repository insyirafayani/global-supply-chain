<x-guest-layout>
    <div class="text-center mb-8 mt-2">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            Welcome to GERIP
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            Monitor global supply chain risks, weather, economy, ports, currency, and trade intelligence in one platform.
        </p>
    </div>

    <div class="flex flex-col space-y-4 pb-2">
        <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-md">
            {{ __('Login') }}
        </a>

        <a href="{{ route('register') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-sm text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-sm">
            {{ __('Register') }}
        </a>
    </div>
</x-guest-layout>
