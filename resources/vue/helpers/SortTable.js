class SortTable {

    constructor() {
        this.sortKey = '';
        this.sortReverse = false;
    }

    sortIcon(column) {
        let icon = 'sort';

        if(this.sortKey == column) {
            icon += (!this.sortReverse) ? '-alpha-asc' : '-alpha-desc';
        }

        return ['fa', 'fa-' + icon];
    }

    sortBy(column) {
        this.sortReverse = (this.sortKey == column) ? !this.sortReverse : false;
        this.sortKey = column;
    }

    orderBy(list) {
        let order = (this.sortReverse) ? 'desc' : 'asc';
        return _.orderBy(list, this.sortKey, order);
    }

}

export default SortTable;