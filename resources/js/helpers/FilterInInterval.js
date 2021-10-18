export default function (start_date_string, end_date_string) {
    let start_date = new Date(start_date_string);
    let end_date = new Date(end_date_string);

    return function (el) {
        let el_start_date = new Date(el.start_datetime);
        let el_end_date = new Date(el.end_datetime);

        if (el_start_date >= start_date && el_start_date <= end_date
            || end_date >= el_start_date && start_date <= el_end_date) {
            return true;
        }
    }
}