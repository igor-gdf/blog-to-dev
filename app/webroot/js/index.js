function setClassColor(element, status) {
    if (status === 'published') {
        element.classList.add('text-success');
    } else if (status === 'draft') {
        element.classList.add('text-warning');
    } else {
        element.classList.add('text-black');
    }
}

document.querySelectorAll('.card-footer small:nth-child(2)').forEach(function (element) {
    var status = element.textContent.trim().toLowerCase();
    setClassColor(element, status);
});

function setStatus(status) {
    document.querySelector('input[name="data[Post][status]"]').value = status;
}