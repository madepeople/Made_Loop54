;(function () {
    function setupAjaxSearch()
    {
        if (searchForm) {
            return;
        }

        var $searchInput = $('search_autocomplete');
        if (!$searchInput) {
            return;
        }

        $searchInput.setAttribute('autocomplete', 'off');
        var searchForm = new Varien.searchForm('search_mini_form', 'search', _searchFormPlaceholder);
        searchForm.initAutocomplete(_ajaxResultBaseUrl, 'search_autocomplete');
    }

    $(document).observe('dom:loaded', setupAjaxSearch());
})();