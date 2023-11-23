document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.remove();
        });
    }, 2000);
});


const alertPlaceholder = document.getElementById('alertPlaceholder');

/**
 * @param {''} message - message to show
 * @param {''} type - alert type, available alerts types - [primary, secondary, success, danger, warning, info, light, dark]
 *  
 */
function showAlert(message, type){
    const wrapper = document.createElement('div');

    wrapper.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" id="alert">
            <div class="d-flex justify-content-center">${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `
    alertPlaceholder.append(wrapper);

    // hide alert after 3s
    hideAlert(3000);
}

/**
 * @param {number} timeout - alert auto close time in ms
 */
function hideAlert(timeout) {
    const alertList = document.querySelectorAll('.alert')
    const alerts = [...alertList].map(element => new bootstrap.Alert(element))

    alerts.forEach(element => {
        setTimeout(() => {
            element.close();
        }, timeout)
    });
}