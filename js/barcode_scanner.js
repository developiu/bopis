/**
 * Ritorna quanto rilavato dal barcode scanner. Eventuali input da tastiera non vengono
 * presi in considerazione.
 *
 * @param overlay un nodo di un elemento html che fungerà da overlay; la funzione si occuperà di spostare il focus a questo
 *                elemento e di renderlo visibile (tabindex dovrà essere impostato a -1 perché questo funzioni); tutto il
 *                resto dovrà essere stato predisposto via css. Può anche essere un selettore che punta a tale nodo.
 *                È fondamentale perché altrimenti l'utente potrebbe spostare il focus presso un altro elemento e la
 *                rivelazione del barcode scanner non funzionerebbe più.
 * @return Promise se l'utente ha premuto escape o return senza avere inserito nulla verrà chiamato il catch,
 *                 altrimenti il then con argomento la stringa inserita
 */
function get_from_barcode_scanner(overlay) {
    /* helper functions */
    function exit_from_scan(overlay) {
        overlay.hide();
        overlay.unbind("keyup");
    }

    // se è passato troppo tempo dall'ultimo carattere inserito cancelliamo il buffer
    function deleteBufferFromHumanInput(buffer, keyEvent) {
        if(typeof  this.lastCharTimestamp == 'undefined') {
            this.lastCharTimestamp = performance.now();
        }
        let now = performance.now();
        if (now - this.lastCharTimestamp > 50) {
            buffer = "";
        }
        this.lastCharTimestamp = now;

        return buffer;
    }

    // aggiungiamo il carattere appena inserito al buffer ma se è passato troppo tempo dall'ultimo carattere inserito
    // cancelliamo prima quello che è stato inserito fino ad ora
    function updateBuffer(buffer, keyEvent) {
        // non consideriamo i caratteri particolari come shift, capslock etc
        if(keyEvent.key.length == 1) {
            buffer += keyEvent.key;
        }
        return buffer;
    }

    /* end of helper functions */

    /* start of main processing */
    if(typeof overlay == 'string') {
        overlay = jQuery(overlay);
    }

    return new Promise( (resolve, reject) => {
        let buffer = "";
        overlay.keyup(function(e) {
            buffer = deleteBufferFromHumanInput(buffer, e);

            if(e.key == 'Escape' || (e.key == 'Enter' && buffer == ''))  {
                 exit_from_scan(overlay);
                 reject();
            }
            if(e.key == 'Enter') {
                // se siamo qui buffer non è vuoto
                exit_from_scan(overlay);
                resolve(buffer);
            }
            buffer = updateBuffer(buffer, e);
        });
        overlay.show();
        overlay.focus();
    });

    if(typeof overlay == 'string') {
        overlay = jQuery(overlay);
    }

}

