<div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
    <label class="whitespace-nowrap">Exibir:</label>
    <select x-model="perPage" @change="setPerPage($event.target.value)"
            class="rounded-lg border border-gray-200 bg-white py-1 px-2 text-xs font-semibold text-gray-700 outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="all">Todas</option>
    </select>
</div>
