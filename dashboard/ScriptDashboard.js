$(document).ready(function () {
    // Activate tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Evento change para el checkbox principal (seleccionar todo)
    $(document).on('change', '#selectAll', function () {
        var isChecked = $(this).prop("checked");
        $('table tbody input[type="checkbox"]').prop("checked", isChecked);
    });

    // Evento change para las casillas de verificación individuales
    $(document).on('change', 'table tbody input[type="checkbox"]', function () {
        var allChecked = $('table tbody input[type="checkbox"]:checked').length === $('table tbody input[type="checkbox"]').length;
        $("#selectAll").prop("checked", allChecked);
    });
});
