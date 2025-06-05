/**
 * Token management JavaScript module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    return {
        init: function(moduleId, userId) {
            // Fetch and display token balance
            Ajax.call([{
                methodname: 'mod_blockchain_get_token_balance',
                args: { userid: userId, moduleid: moduleId },
                done: function(response) {
                    if (response.error) {
                        Notification.alert(
                            M.str.mod_blockchain.error,
                            response.error,
                            M.str.mod_blockchain.ok
                        );
                        $('#token-balance').text('0').addClass('text-danger').removeClass('text-success');
                        return;
                    }

                    $('#token-balance').text(response.balance);
                    if (response.balance > 0) {
                        $('#token-balance').addClass('text-success').removeClass('text-danger');
                    } else {
                        $('#token-balance').addClass('text-danger').removeClass('text-success');
                    }
                },
                fail: function(exception) {
                    Notification.exception(exception);
                    $('#token-balance').text('0').addClass('text-danger').removeClass('text-success');
                }
            }]);

            // Fetch and display transaction history
            Ajax.call([{
                methodname: 'mod_blockchain_get_transaction_history',
                args: { userid: userId, moduleid: moduleId },
                done: function(response) {
                    var $table = $('#transaction-history-table tbody');
                    $table.empty();

                    if (response.error) {
                        $table.append(
                            '<tr><td colspan="4">' + response.error + '</td></tr>'
                        );
                        return;
                    }

                    if (response.transactions.length > 0) {
                        $.each(response.transactions, function(index, tx) {
                            $table.append(
                                '<tr>' +
                                '<td>' + tx.amount + '</td>' +
                                '<td>' + tx.reason + '</td>' +
                                '<td>' + tx.tx_hash + '</td>' +
                                '<td>' + new Date(tx.timecreated * 1000).toLocaleString() + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        $table.append(
                            '<tr><td colspan="4">' + M.str.mod_blockchain.no_transactions + '</td></tr>'
                        );
                    }
                },
                fail: function(exception) {
                    Notification.exception(exception);
                    $('#transaction-history-table tbody').empty().append(
                        '<tr><td colspan="4">' + M.str.mod_blockchain.error_fetching_history + '</td></tr>'
                    );
                }
            }]);
        }
    };
});