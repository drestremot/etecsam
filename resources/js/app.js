import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Componente de tabela admin (busca + ordenação + seleção em massa + paginação 10/25/50/todas + estado indeterminate)
window.adminTable = function() {
    return {
        q: '',
        sortCol: '',
        sortDir: 'asc',
        rows: [],
        filteredRows: [],
        selected: [],
        perPage: '25',
        page: 1,

        init() {
            this.rows = [...this.$el.querySelectorAll('tbody tr[data-row]')];
            this.filteredRows = [...this.rows];
            this.applySort();
            this.paginate();

            // Sincroniza o master checkbox e os itens sempre que 'selected' mudar
            this.$watch('selected', () => {
                this.syncCheckboxes();
            });

            // Sincroniza na inicialização
            this.$nextTick(() => {
                this.syncCheckboxes();
            });
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

        get masterCheckbox() {
            if (!this.$el) return null;
            return this.$el.querySelector('thead input[type="checkbox"][data-bulk-master]') ||
                   this.$el.querySelector('thead input[type="checkbox"]');
        },

        get visibleBulkCheckboxes() {
            if (!this.$el) return [];
            return [...this.$el.querySelectorAll('tbody tr:not(.hidden) input[type="checkbox"][data-bulk-item]')];
        },

        get allSelected() {
            const vCbs = this.visibleBulkCheckboxes;
            if (vCbs.length === 0) return false;
            const selSet = new Set(this.selected.map(String));
            return vCbs.every(cb => selSet.has(String(cb.value)));
        },

        get isIndeterminate() {
            const vCbs = this.visibleBulkCheckboxes;
            if (vCbs.length === 0) return false;
            const selSet = new Set(this.selected.map(String));
            const count = vCbs.filter(cb => selSet.has(String(cb.value))).length;
            return count > 0 && count < vCbs.length;
        },

        syncMasterCheckbox() {
            const master = this.masterCheckbox;
            if (!master) return;

            const vCbs = this.visibleBulkCheckboxes;
            if (vCbs.length === 0) {
                master.checked = false;
                master.indeterminate = false;
                return;
            }

            const selSet = new Set(this.selected.map(String));
            const count = vCbs.filter(cb => selSet.has(String(cb.value))).length;

            if (count === 0) {
                master.checked = false;
                master.indeterminate = false;
            } else if (count === vCbs.length) {
                master.checked = true;
                master.indeterminate = false;
            } else {
                master.checked = false;
                master.indeterminate = true;
            }
        },

        syncCheckboxes() {
            const selSet = new Set(this.selected.map(String));
            this.$el.querySelectorAll('tbody input[type="checkbox"][data-bulk-item]').forEach(cb => {
                cb.checked = selSet.has(String(cb.value));
            });
            this.syncMasterCheckbox();
        },

        toggleSelectAll() {
            const vCbs = this.visibleBulkCheckboxes;
            if (vCbs.length === 0) return;

            const selSet = new Set(this.selected.map(String));
            const count = vCbs.filter(cb => selSet.has(String(cb.value))).length;
            const isAllSelected = count === vCbs.length;
            const isIndeterminate = count > 0 && count < vCbs.length;

            const visibleValues = vCbs.map(cb => String(cb.value));

            if (isAllSelected || isIndeterminate) {
                // Desmarcar todos os visíveis (quando estiver marcado ou indeterminado)
                const visibleSet = new Set(visibleValues);
                this.selected = this.selected.map(String).filter(id => !visibleSet.has(id));
                vCbs.forEach(cb => { cb.checked = false; });
            } else {
                // Selecionar todos os visíveis
                const combined = new Set([...this.selected.map(String), ...visibleValues]);
                this.selected = Array.from(combined);
                vCbs.forEach(cb => { cb.checked = true; });
            }

            this.syncMasterCheckbox();
        },

        clearSelection() {
            this.selected = [];
            this.$el.querySelectorAll('tbody input[type="checkbox"][data-bulk-item]').forEach(cb => {
                cb.checked = false;
            });
            this.syncMasterCheckbox();
        },

        setPerPage(val) {
            this.perPage = val;
            this.page = 1;
            this.paginate();
        },

        goToPage(p) {
            if (p < 1 || p > this.totalPages) return;
            this.page = p;
            this.paginate();
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
            } else {
                const pp = parseInt(this.perPage, 10);
                const start = (this.page - 1) * pp;
                const end = start + pp;
                const pageRows = this.filteredRows.slice(start, end);

                this.rows.forEach(r => {
                    const isVisible = pageRows.includes(r);
                    r.classList.toggle('hidden', !isVisible);
                });
            }
            this.syncCheckboxes();
        },

        search() {
            const q = this.q.toLowerCase().trim();
            this.filteredRows = this.rows.filter(r => {
                return !q || (r.dataset.row || '').toLowerCase().includes(q);
            });
            this.page = 1;
            this.paginate();
        },

        sort(col) {
            if (this.sortCol === col) {
                this.sortDir = this.sortDir === 'desc' ? 'asc' : 'desc';
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
