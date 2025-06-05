/**
 * Document management JavaScript module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Academical Blockchain
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    return {
        init: function(moduleId) {
            // Initialize document status checks
            $('.blockchain-status').each(function() {
                var $element = $(this);
                var documentId = $element.data('documentid');

                if (documentId) {
                    // Fetch document verification status
                    Ajax.call([{
                        methodname: 'mod_blockchain_check_document_status',
                        args: { documentid: documentId },
                        done: function(response) {
                            if (response.verified) {
                                $element.removeClass('pending').addClass('verified')
                                    .find('.badge').removeClass('bg-warning').addClass('bg-success')
                                    .text(M.str.mod_blockchain.verified);
                            } else {
                                $element.removeClass('verified').addClass('pending')
                                    .find('.badge').removeClass('bg-success').addClass('bg-warning')
                                    .text(M.str.mod_blockchain.pending);
                            }
                        },
                        fail: Notification.exception
                    }]);
                }
            });

            // Handle document upload form submission
            $('#document-upload-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: M.cfg.wwwroot + '/mod/blockchain/ajax.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Notification.alert(
                                M.str.mod_blockchain.success,
                                M.str.mod_blockchain.document_uploaded,
                                M.str.mod_blockchain.ok
                            );
                            window.location.reload();
                        } else {
                            Notification.alert(
                                M.str.mod_blockchain.error,
                                response.error || M.str.mod_blockchain.upload_failed,
                                M.str.mod_blockchain.ok
                            );
                        }
                    },
                    error: Notification.exception
                });
            });
        }
    };
});