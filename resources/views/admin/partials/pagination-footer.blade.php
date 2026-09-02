<!-- Pagination Footer -->
<div class="px-5 py-3 border-t border-gray-100 bg-gray-50/70 flex flex-wrap items-center justify-between gap-3 text-xs text-gray-600">
    <div>
        Exibindo <span class="font-bold text-gray-800" x-text="startItem"></span> a <span class="font-bold text-gray-800" x-text="endItem"></span> de <span class="font-bold text-gray-800" x-text="totalItems"></span> registro(s)
    </div>
    <div class="flex items-center gap-1" x-show="totalPages > 1">
        <button type="button" @click="prevPage()" :disabled="page <= 1"
                class="px-2.5 py-1 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed font-medium transition shadow-2xs">
            Anterior
        </button>
        <template x-for="p in totalPages" :key="p">
            <button type="button" @click="goToPage(p)"
                    :class="page === p ? 'bg-indigo-600 text-white font-bold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                    class="w-7 h-7 rounded-lg text-xs font-medium flex items-center justify-center transition shadow-2xs"
                    x-text="p">
            </button>
        </template>
        <button type="button" @click="nextPage()" :disabled="page >= totalPages"
                class="px-2.5 py-1 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed font-medium transition shadow-2xs">
            Próxima
        </button>
    </div>
</div>