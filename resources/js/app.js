import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Componente de tabela admin (busca + ordenação + seleção em massa)
window.adminTable = function() {
    return {
        q: '',
        sortCol: '',
        sortDir: 'asc',
        rows: [],
        selected: [],
        allSelected: false,

        init() {
            this.rows = [...this.$el.querySelectorAll('tbody tr[data-row]')];
            this.applySort();
        },

        toggleSelectAll() {
            const checkboxes = [...this.$el.querySelectorAll('tbody tr:not(.hidden) input[type="checkbox"][data-bulk-item]')];
            if (this.allSelected) {
                this.selected = checkboxes.map(cb => String(cb.value));
            } else {
                this.selected = [];
            }
        },

        updateSelectAll() {
            const checkboxes = [...this.$el.querySelectorAll('tbody tr:not(.hidden) input[type="checkbox"][data-bulk-item]')];
            const selSet = new Set(this.selected.map(String));
            this.allSelected = checkboxes.length > 0 && checkboxes.every(cb => selSet.has(String(cb.value)));
        },

        clearSelection() {
            this.selected = [];
            this.allSelected = false;
        },

        search() {
            const q = this.q.toLowerCase().trim();
            this.rows.forEach(r => {
                const match = !q || (r.dataset.row || '').toLowerCase().includes(q);
                r.classList.toggle('hidden', !match);
            });
        },

        sort(col) {
            if (this.sortCol === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = col;
                this.sortDir = 'asc';
            }
            this.applySort();
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
