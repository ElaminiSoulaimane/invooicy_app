   <button
       {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-perf-black border border-transparent rounded-[25px] font-semibold text-xs text-perf-green uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-perf-green  focus:ring-offset-2 transition ease-in-out duration-150']) }}>
       {{ $slot }}
   </button>
