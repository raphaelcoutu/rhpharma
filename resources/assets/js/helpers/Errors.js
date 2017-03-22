class Errors {

    constructor() {
        this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field);

    }

    get(field) {
        if(field) {
            if(this.errors[field]) {
                return this.errors[field][0];
            }

            return;
        }

        let errorFields = Object.keys(this.errors);
        if(errorFields.length > 0) {
            return this.errors[errorFields[0]][0];
        }
    }

    clear(field) {
        if(field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};

    }

    any() {
        return Object.keys(this.errors).length > 0;
    }

    record(errors) {
        this.errors = errors;
    }

}

export default Errors;
