import './bootstrap';

document.getElementById('go-to-page-form')!.addEventListener('submit', (e) => {
    e.preventDefault();
    let pageIdInput = document.getElementById('page-id') as HTMLInputElement;
    let pageId = pageIdInput.value;
    
    window.location.href = '/page/' + pageId;
});
