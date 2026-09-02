import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Componente de tabela admin (busca + ordenação + seleção em massa + paginação 10/25/50/todas)
window.adminTable = function() {
    return {
        q: '',
        sortCol: '',
        sortDir: 'asc',
        rows: [],
        filteredRows: [],
        selected: [],
        allSelected: false,
        perPage: '25',
        page: 1,

        init() {
            this.rows = [...this.$el.querySelectorAll('tbody tr[data-row]')];
            this.filteredRows = [...this.rows];
            this.applySort();
            this.paginate();
        },

        get totalItems() {
            return this.filteredRows.length;
        },

        get totalPages() {
            if (this.perPage === 'all' || !this.perPage) return 1;
            const pp = parseInt(this.perPage, 10);
            return Math.max(1, Math.ceil(this.filteredRows.length / pp));
        },

        get startItem() {
            if (this.filteredRows.length === 0) return 0;
            if (this.perPage === 'all') return 1;
            const pp = parseInt(this.perPage, 10);
            return (this.page - 1) * pp + 1;
        },

        get endItem() {
            if (this.perPage === 'all') return this.filteredRows.length;
            const pp = parseInt(this.perPage, 10);
            return Math.min(this.page * pp, this.filteredRows.length);
        },

        setPerPage(val) {
            this.perPage = val;
            this.page = 1;
            this.paginate();
            this.updateSelectAll();
        },

        goToPage(p) {
            if (p < 1 || p > this.totalPages) return;
            this.page = p;
            this.paginate();
            this.updateSelectAll();
        },

        nextPage() {
            if (this.page < this.totalPages) {
                this.goToPage(this.page + 1);
            }
        },

        prevPage() {
            if (this.page > 1) {
                this.goToPage(this.page - 1);
            }
        },

        paginate() {
            if (this.perPage === 'all') {
                this.rows.forEach(r => {
                    const isFiltered = this.filteredRows.includes(r);
                    r.classList.toggle('hidden', !isFiltered);
                });
                return;
            }

            const pp = parseInt(this.perPage, 10);
            const start = (this.page - 1) * pp;
            const end = start + pp;
            const pageRows = this.filteredRows.slice(start, end);

            this.rows.forEach(r => {
                const isVisible = pageRows.includes(r);
                r.classList.toggle('hidden', !isVisible);
            });
        },

        toggleSelectAll() {
            const visibleCheckboxes = [...this.$el.querySelectorAll('tbody tr:not(.hidden) input[type="checkbox"][data-bulk-item]')];
            if (this.allSelected) {
                this.selected = visibleCheckboxes.map(cb => String(cb.value));
            } else {
                this.selected = [];
            }
        },

        updateSelectAll() {
            const visibleCheckboxes = [...this.$el.querySelectorAll('tbody tr:not(.hidden) input[type="checkbox"][data-bulk-item]')];
            const selSet = new Set(this.selected.map(String));
            this.allSelected = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => selSet.has(String(cb.value)));
        },

        clearSelection() {
            this.selected = [];
            this.allSelected = false;
        },

        search() {
            const q = this.q.toLowerCase().trim();
            this.filteredRows = this.rows.filter(r => {
                return !q || (r.dataset.row || '').toLowerCase().includes(q);
            });
            this.page = 1;
            this.paginate();
            this.updateSelectAll();
        },

        sort(col) {
            if (this.sortCol === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = col;
                this.sortDir = 'asc';
            }
            this.applySort();
            this.paginate();
        },

        applySort() {
            const tbody = this.$el.querySelector('tbody');
            if (!tbody) return;

            const sorted = [...this.rows].sort((a, b) => {
                const aActive = (a.dataset.active ?? '1') !== '0';
                const bActive = (b.dataset.active ?? '1') !== '0';
                if (aActive !== bActive) return aActive ? -1 : 1;

                if (!this.sortCol) return 0;

                const av = (a.dataset[this.sortCol] || '').toLowerCase();
                const bv = (b.dataset[this.sortCol] || '').toLowerCase();
                const cmp = av.localeCompare(bv, 'pt-BR', { numeric: true, sensitivity: 'base' });
                return this.sortDir === 'asc' ? cmp : -cmp;
            });

            this.rows = sorted;
            sorted.forEach(r => tbody.appendChild(r));
        },

        icon(col) {
            if (this.sortCol !== col) return '↕';
            return this.sortDir === 'asc' ? '↑' : '↓';
        }
    };
};

Alpine.data('adminTable', window.adminTable);

Alpine.start();
