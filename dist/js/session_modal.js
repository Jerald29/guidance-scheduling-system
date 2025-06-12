$(document).ready(function() {
    $('.btn-view').click(function() {
        var sessionNumber = $(this).data('session_number');
        var sessionDate = $(this).data('session_date');
        var sessionContent = $(this).data('content');
        var sessionObservation = $(this).data('observation');
        var sessionReco_name = $(this).data('reco_name');
        var sessionOthers = $(this).data('session_others');

        $('#session_number').text(sessionNumber);
        $('#session_date').text(sessionDate);
        $('#content').text(sessionContent);
        $('#observation').text(sessionObservation);
        $('#reco_name').text(sessionReco_name);
        $('#session_others').text(sessionOthers);

        $('#viewModal').modal('show');
    });
});