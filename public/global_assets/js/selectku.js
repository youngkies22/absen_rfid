
// Default initialization
$('.select').select2({ minimumResultsForSearch: Infinity });
// Select with search
$('.select-search').select2();
// Fixed width. Single select
$('.select-fixed-single').select2({
  minimumResultsForSearch: Infinity,
  width: 250
});
// Fixed width. Multiple selects
$('.select-fixed-multiple').select2({
  minimumResultsForSearch: Infinity,
  width: 400
});
//date picker singel
$('.daterange-single').daterangepicker({ singleDatePicker: true });