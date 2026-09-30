@props(['count'])
{{--
    admin/includes/custom-pagination-links.blade.php for a list that always
    fits on one page: the real site still shows the footer (Prev/Next
    disabled, a highlighted "1", the row-count line and the per-page select)
    whenever there's at least one row.
--}}
{{ (new \Illuminate\Pagination\LengthAwarePaginator($count > 0 ? range(1, $count) : [], $count, max($count, 1), 1))->links('pharmacy.partials.pagination-links') }}
